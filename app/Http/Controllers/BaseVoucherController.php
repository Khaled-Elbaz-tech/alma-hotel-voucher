<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Employee;
use App\Models\LookupCategory;
use App\Models\Voucher;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Mail\VoucherGeneratedMail;
use App\Mail\VoucherImageMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

abstract class BaseVoucherController extends Controller
{
    protected string $voucherType = 'hotel';
    protected string $voucherPrefix = 'ALMA-HV';
    protected string $createView = 'vouchers.create';
    protected string $showView = 'vouchers.show';
    protected string $pdfView = 'vouchers.pdf';
    protected string $layoutView = 'vouchers.partials.voucher-layout';
    protected string $photoField = 'hotel_photo';
    protected string $photoToggleField = 'include_hotel_photo';
    protected array $validationRules = [];
    protected array $requiredFields = [];
    protected array $optionalFields = [];

    public function create(): View
    {
        $settings = CompanySetting::firstOrFail();
        $categories = LookupCategory::with('activeValues')->orderBy('sort_order')->orderBy('name')->get();
        $employees = Employee::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        return view($this->createView, compact('settings', 'categories', 'employees'));
    }

    public function copy(Voucher $voucher): RedirectResponse
    {
        // Only the owner (or admin) can copy a voucher
        if ($voucher->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }
        $routeMap = [
            'hotel' => 'hotel-voucher.create',
            'car-rental' => 'car-rental-voucher.create',
            'transfers' => 'transfers-voucher.create',
            'vip-meet-assist' => 'vip-meet-assist-voucher.create',
        ];
        $data = $voucher->toArray();
        // Pre-select the company dropdown for credit vouchers
        if ($voucher->client_type === 'credit' && $voucher->client_company) {
            $data['company_select'] = $voucher->client_company;
        }
        if ($voucher->client_type === 'cash' && $voucher->client_company) {
            $data['company_name_cash'] = $voucher->client_company;
        }
        return redirect()->route($routeMap[$this->voucherType] ?? 'hotel-voucher.create')->withInput($data);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->getValidationRules());

        $checkIn = Carbon::parse($this->resolveCheckInDate($validated));
        $checkOut = Carbon::parse($this->resolveCheckOutDate($validated));
        $voucherNumber = $this->generateVoucherNumber();
        $photoPath = null;

        if (! empty($validated[$this->photoToggleField] ?? false) && $request->hasFile($this->photoField)) {
            $photoPath = $request->file($this->photoField)->store('vouchers/'.$this->voucherType.'-photos', 'public');
        }

        $voucherData = $this->prepareVoucherData($validated, $voucherNumber, $photoPath, $checkIn, $checkOut);
        $voucherData['user_id'] = $request->user()->id;
        // Client & supplier (form-only fields, not on the voucher)
        $voucherData['client_type'] = $validated['client_type'];
        $voucherData['client_company'] = $validated['client_type'] === 'credit'
            ? $this->resolveOption($validated['company_select'], $validated['company_select_other'] ?? null)
            : $validated['company_name_cash'];
        $voucherData['supplier'] = $validated['supplier'];
        $voucher = Voucher::create($voucherData);

        // The voucher email is sent from the show page once the voucher
        // image + PDF are generated (see savePdf / emailPdf).

        return redirect()->route($this->voucherType.'-voucher.show', $voucher)
            ->with('success', 'Voucher '.$voucherNumber.' generated successfully.');
    }

    public function show(Voucher $voucher): View
    {
        $settings = CompanySetting::firstOrFail();

        // Embed images as data URIs so the on-screen voucher never depends on
        // the public/storage symlink or on uploaded asset paths being present.
        $hotelPhotoData = $this->imageDataUri($voucher->hotel_photo_path);
        $heroImageData = $this->publicImageDataUri('images/vouchers/'.$this->voucherType.'-hero.jpg');

        return view($this->showView, compact('voucher', 'settings', 'hotelPhotoData', 'heroImageData'));
    }


    public function downloadPdf(Voucher $voucher): BinaryFileResponse
    {
        $path = $this->generateVoucherPdf($voucher);
        $fileName = $voucher->voucher_number.'.pdf';

        return response()->download(storage_path('app/public/'.$path), $fileName);
    }

    protected function generateVoucherPdf(Voucher $voucher): string
    {
        $settings = CompanySetting::firstOrFail();
        $fileName = $voucher->voucher_number.'.pdf';
        $path = 'vouchers/pdfs/'.$fileName;
        $logoData = $this->imageDataUri($settings->logo_path);
        $hotelPhotoData = $this->imageDataUri($voucher->hotel_photo_path);
        // Hero fallback image for PDF (when no photo uploaded)
        $heroImageData = $this->publicImageDataUri('images/vouchers/'.$this->voucherType.'-hero.jpg');
        $voucherCss = file_get_contents(resource_path('css/voucher.css'));

        if ($voucherCss === false) {
            abort(500, 'Voucher stylesheet is unavailable.');
        }

        Storage::disk('public')->makeDirectory('vouchers/pdfs');
        Pdf::loadView($this->pdfView, compact('voucher', 'settings', 'logoData', 'hotelPhotoData', 'heroImageData', 'voucherCss'))
            ->setPaper('a4', 'landscape')
            ->setBasePath(public_path())
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false)
            ->save(storage_path('app/public/'.$path));

        $voucher->update(['pdf_path' => $path]);

        return $path;
    }

    public function savePng(Request $request, Voucher $voucher): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'string', 'max:20000000'],
        ]);

        if (! preg_match('/^data:image\/(?:png|jpeg);base64,/', $validated['image'])) {
            return response()->json(['message' => 'Invalid image data.'], 422);
        }

        $image = preg_replace('/^data:image\/(?:png|jpeg);base64,/', '', $validated['image']);
        $decoded = base64_decode($image, true);

        if ($decoded === false) {
            return response()->json(['message' => 'Unable to decode image.'], 422);
        }

        $fileName = $voucher->voucher_number.(str_contains($validated['image'], 'data:image/jpeg') ? '.jpg' : '.png');
        $path = 'vouchers/pngs/'.$fileName;
        Storage::disk('public')->makeDirectory('vouchers/pngs');
        Storage::disk('public')->put($path, $decoded, 'public');
        $voucher->update(['png_path' => $path]);

        return response()->json(['path' => $path]);
    }

    public function savePdf(Request $request, Voucher $voucher): JsonResponse
    {
        try {
            $validated = $request->validate([
                'pdf' => ['required', 'string', 'max:60000000'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('savePdf rejected for '.$voucher->voucher_number.': '.json_encode($e->errors()).' | content-length: '.($request->header('Content-Length') ?? 'n/a'));
            return response()->json(['message' => 'Voucher file rejected: '.collect($e->errors())->flatten()->first()], 422);
        }

        $pdf = preg_replace('/^data:application\/pdf[^,]*,/', '', $validated['pdf']);
        $decoded = base64_decode($pdf, true);

        if ($decoded === false || ! str_starts_with($decoded, '%PDF')) {
            \Log::warning('savePdf invalid PDF payload for '.$voucher->voucher_number.' (len '.strlen($validated['pdf']).')');
            return response()->json(['message' => 'Invalid PDF data.'], 422);
        }

        $fileName = $voucher->voucher_number.'.pdf';
        $path = 'vouchers/pdfs/'.$fileName;
        Storage::disk('public')->makeDirectory('vouchers/pdfs');
        Storage::disk('public')->put($path, $decoded, 'public');
        $voucher->update(['pdf_path' => $path]);

        // Send the voucher email right here, server-side, so it does not
        // depend on a second page load in the browser.
        $emailResult = ['emailed' => false];
        try {
            $voucher->refresh();
            $toEmail = $voucher->user?->email;
            if ($toEmail) {
                \Illuminate\Support\Facades\Mail::to($toEmail)->send(new VoucherImageMail($voucher));
                $emailResult = ['emailed' => true];
            } else {
                $emailResult = ['emailed' => false, 'error' => 'No user email found'];
            }
        } catch (\Throwable $e) {
            \Log::error('Voucher email failed for '.$voucher->voucher_number.': '.$e->getMessage());
            $emailResult = ['emailed' => false, 'error' => $e->getMessage()];
        }

        return response()->json(array_merge(['path' => $path, 'saved' => true], $emailResult));
    }

    public function emailPdf(Voucher $voucher): RedirectResponse
    {
        if (!$voucher->pdf_path && !$voucher->png_path) {
            return redirect()->back()->with('error', 'No voucher file found. Please generate it first.');
        }
        try {
            $user = $voucher->user;
            $toEmail = $user ? $user->email : null;
            if (!$toEmail) {
                return redirect()->back()->with('error', 'No recipient email.');
            }
            \Illuminate\Support\Facades\Mail::to($toEmail)
                ->send(new VoucherImageMail($voucher));
            return redirect()->back()->with('success', 'Voucher emailed to '.$toEmail);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Email failed: '.$e->getMessage());
        }
    }

    public function destroy(Voucher $voucher): RedirectResponse
    {
        foreach (['hotel_photo_path', 'pdf_path', 'png_path'] as $pathField) {
            if ($voucher->{$pathField}) {
                Storage::disk('public')->delete($voucher->{$pathField});
            }
        }

        $voucher->delete();

        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher log deleted.');
    }

    protected function getValidationRules(): array
    {
        return array_merge([
            'guest_name' => ['required', 'string', 'max:120'],
            'voucher_theme' => ['required', 'in:modern,premium,premiumred,maroon,crimson,slate'],
            'client_type' => ['required', 'in:credit,cash'],
            'company_select' => ['nullable', 'string', 'max:160', 'required_if:client_type,credit'],
            'company_select_other' => ['nullable', 'string', 'max:160', 'required_if:company_select,Other'],
            'company_name_cash' => ['nullable', 'string', 'max:160', 'required_if:client_type,cash'],
            'supplier' => ['required', 'string', 'max:160'],
        ], $this->validationRules);
    }

    abstract protected function prepareVoucherData(array $validated, string $voucherNumber, ?string $photoPath, Carbon $checkIn, Carbon $checkOut): array;

    protected function resolveCheckInDate(array $validated): string
    {
        return $validated['check_in_date'];
    }

    protected function resolveCheckOutDate(array $validated): string
    {
        return $validated['check_out_date'];
    }

    protected function resolveOption(string $selected, ?string $other): string
    {
        return $selected === 'Other' && trim((string) $other) !== '' ? trim($other) : $selected;
    }

    protected function generateVoucherNumber(): string
    {
        $date = now()->format('Ymd');

        do {
            $number = $this->voucherPrefix.'-'.$date.'-'.random_int(1000, 9999);
        } while (Voucher::where('voucher_number', $number)->exists());

        return $number;
    }

    protected function imageDataUri(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $mime = Storage::disk('public')->mimeType($path);
        $contents = Storage::disk('public')->get($path);

        return $contents === false || ! $mime ? null : 'data:'.$mime.';base64,'.base64_encode($contents);
    }

    /**
     * Data URI for a file that lives in the public/ web folder
     * (e.g. images/vouchers/hotel-hero.jpg) — NOT on the public storage disk.
     */
    protected function publicImageDataUri(string $relativePath): ?string
    {
        $fullPath = public_path($relativePath);

        if (! is_file($fullPath)) {
            return null;
        }

        $mime = mime_content_type($fullPath);
        $contents = file_get_contents($fullPath);

        return $contents === false || ! $mime ? null : 'data:'.$mime.';base64,'.base64_encode($contents);
    }
}