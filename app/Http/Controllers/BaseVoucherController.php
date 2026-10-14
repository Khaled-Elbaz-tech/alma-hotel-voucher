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
        $voucher = Voucher::create($voucherData);

        // Send professional HTML email (email-client friendly)
        try {
            $toEmail = $request->user()->email;
            $vNum = $voucher->voucher_number;
            \Illuminate\Support\Facades\Mail::html(
                view('emails.voucher-html', ['voucher' => $voucher])->render(),
                function ($m) use ($toEmail, $vNum) {
                    $m->to($toEmail)->subject("Alma Travel Voucher $vNum");
                }
            );
        } catch (\Throwable $e) {
            \Log::warning('HTML voucher email failed: '.$e->getMessage());
        }

        return redirect()->route($this->voucherType.'-voucher.show', $voucher)
            ->with('success', 'Voucher '.$voucherNumber.' generated successfully.');
    }

    public function show(Voucher $voucher): View
    {
        $settings = CompanySetting::firstOrFail();

        return view($this->showView, compact('voucher', 'settings'));
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
        $heroImageData = $this->imageDataUri('images/vouchers/'.$this->voucherType.'-hero.jpg');
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

        $fileName = $voucher->voucher_number.'.png';
        $path = 'vouchers/pngs/'.$fileName;
        Storage::disk('public')->makeDirectory('vouchers/pngs');
        Storage::disk('public')->put($path, $decoded, 'public');
        $voucher->update(['png_path' => $path]);

        return response()->json(['path' => $path]);
    }

    public function savePdf(Request $request, Voucher $voucher): JsonResponse
    {
        $validated = $request->validate([
            'pdf' => ['required', 'string', 'max:20000000'],
        ]);

        if (! preg_match('/^data:application\/pdf;base64,/', $validated['pdf'])) {
            return response()->json(['message' => 'Invalid PDF data.'], 422);
        }

        $pdf = preg_replace('/^data:application\/pdf;base64,/', '', $validated['pdf']);
        $decoded = base64_decode($pdf, true);

        if ($decoded === false) {
            return response()->json(['message' => 'Unable to decode PDF.'], 422);
        }

        $fileName = $voucher->voucher_number.'.pdf';
        $path = 'vouchers/pdfs/'.$fileName;
        Storage::disk('public')->makeDirectory('vouchers/pdfs');
        Storage::disk('public')->put($path, $decoded, 'public');
        $voucher->update(['pdf_path' => $path]);

        // Email the browser PDF using the voucher's user (not request user)
        $emailResult = ['emailed' => false];
        try {
            $voucherUser = $voucher->user;
            $toEmail = $voucherUser ? $voucherUser->email : null;
            if ($toEmail) {
                $vNum = $voucher->voucher_number;
                $pdfFull = storage_path('app/public/'.$path);
                \Illuminate\Support\Facades\Mail::raw("Your voucher $vNum is attached.", function ($m) use ($toEmail, $vNum, $pdfFull) {
                    $m->to($toEmail)->subject("Alma Travel Voucher $vNum");
                    if (file_exists($pdfFull)) {
                        $m->attach($pdfFull, ['as' => $vNum.'.pdf', 'mime' => 'application/pdf']);
                    }
                });
                $emailResult = ['emailed' => true];
            } else {
                $emailResult = ['emailed' => false, 'error' => 'No user email found'];
            }
        } catch (\Throwable $e) {
            $emailResult = ['emailed' => false, 'error' => get_class($e).': '.($e->getMessage() ?: 'no message')];
        }
        return response()->json(array_merge(['path' => $path, 'saved' => true], $emailResult));
    }

    public function emailPdf(Voucher $voucher): RedirectResponse
    {
        $pdfPath = $voucher->pdf_path;
        if (!$pdfPath) {
            return redirect()->back()->with('error', 'No PDF found. Please generate it first.');
        }
        try {
            $user = $voucher->user;
            $toEmail = $user ? $user->email : null;
            if (!$toEmail) {
                return redirect()->back()->with('error', 'No recipient email.');
            }
            $vNum = $voucher->voucher_number;
            $pdfFull = storage_path('app/public/'.$pdfPath);
            \Illuminate\Support\Facades\Mail::raw("Your voucher $vNum is attached.", function ($m) use ($toEmail, $vNum, $pdfFull) {
                $m->to($toEmail)->subject("Alma Travel Voucher $vNum");
                if (file_exists($pdfFull)) {
                    $m->attach($pdfFull, ['as' => $vNum.'.pdf', 'mime' => 'application/pdf']);
                }
            });
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
            'voucher_theme' => ['required', 'in:classic,modern,premium'],
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
}