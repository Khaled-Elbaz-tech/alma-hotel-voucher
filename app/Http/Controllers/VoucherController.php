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
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VoucherController extends Controller
{
    public function create(): View
    {
        $settings = CompanySetting::firstOrFail();
        $categories = LookupCategory::with('activeValues')->orderBy('sort_order')->orderBy('name')->get();
        $employees = Employee::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('vouchers.create', compact('settings', 'categories', 'employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:120'],
            'voucher_theme' => ['required', 'in:classic,modern'],
            'hotel_name' => ['required', 'string', 'max:160'],
            'hotel_address' => ['required', 'string', 'max:500'],
            'hotel_location_sub' => ['nullable', 'string', 'max:160'],
            'hotel_phone' => ['required', 'string', 'max:60'],
            'occupancy' => ['nullable', 'string', 'max:120'],
            'confirmation_reference' => ['required', 'string', 'max:100'],
            'check_in_date' => ['required', 'date', 'before:check_out_date'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'check_in_time' => ['required', 'string', 'max:8'],
            'check_out_time' => ['required', 'string', 'max:8'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
            'meal_plan' => ['required', 'string', 'max:120'],
            'meal_plan_other' => ['nullable', 'string', 'max:120', 'required_if:meal_plan,Other'],
            'room_type' => ['required', 'string', 'max:120'],
            'room_type_other' => ['nullable', 'string', 'max:120', 'required_if:room_type,Other'],
            'cancellation_policy' => ['required', 'string'],
            'cancellation_policy_other' => ['nullable', 'string', 'max:500', 'required_if:cancellation_policy,Other'],
            'include_hotel_photo' => ['nullable', 'boolean'],
            'hotel_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'employee_id' => ['nullable', 'exists:employees,id'],
        ]);

        $checkIn = Carbon::parse($validated['check_in_date']);
        $checkOut = Carbon::parse($validated['check_out_date']);
        $voucherNumber = $this->generateVoucherNumber();
        $photoPath = null;

        if (! empty($validated['include_hotel_photo']) && $request->hasFile('hotel_photo')) {
            $photoPath = $request->file('hotel_photo')->store('vouchers/hotel-photos', 'public');
        }

        $voucher = Voucher::create([
            'voucher_number' => $voucherNumber,
            'voucher_theme' => $validated['voucher_theme'],
            'guest_name' => $validated['guest_name'],
            'hotel_name' => $validated['hotel_name'],
            'hotel_address' => $validated['hotel_address'],
            'hotel_location_sub' => $validated['hotel_location_sub'] ?? null,
            'hotel_phone' => $validated['hotel_phone'],
            'confirmation_reference' => $validated['confirmation_reference'],
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'check_in_time' => $validated['check_in_time'],
            'check_out_time' => $validated['check_out_time'],
            'nights_count' => $checkIn->diffInDays($checkOut),
            'occupancy' => $validated['occupancy'] ?? '1 Room | 2 Adults',
            'meal_plan' => $this->resolveOption($validated['meal_plan'], $validated['meal_plan_other'] ?? null),
            'room_type' => $this->resolveOption($validated['room_type'], $validated['room_type_other'] ?? null),
            'cancellation_policy' => $this->resolveOption($validated['cancellation_policy'], $validated['cancellation_policy_other'] ?? null),
            'special_requests' => $validated['special_requests'] ?? null,
            'include_hotel_photo' => (bool) ($validated['include_hotel_photo'] ?? false),
            'hotel_photo_path' => $photoPath,
            'employee_id' => $validated['employee_id'] ?: null,
        ]);

        return redirect()->route('vouchers.show', $voucher)->with('success', 'Voucher '.$voucherNumber.' generated successfully.');
    }

    public function show(Voucher $voucher): View
    {
        $settings = CompanySetting::firstOrFail();

        return view('vouchers.show', compact('voucher', 'settings'));
    }

    public function downloadPdf(Voucher $voucher): BinaryFileResponse
    {
        $settings = CompanySetting::firstOrFail();
        $fileName = $voucher->voucher_number.'.pdf';
        $path = 'vouchers/pdfs/'.$fileName;
        $logoData = $this->imageDataUri($settings->logo_path);
        $hotelPhotoData = $this->imageDataUri($voucher->hotel_photo_path);
        $voucherCss = file_get_contents(resource_path('css/voucher.css'));
        if ($voucherCss === false) {
            abort(500, 'Voucher stylesheet is unavailable.');
        }

        Storage::disk('public')->makeDirectory('vouchers/pdfs');
        Pdf::loadView('vouchers.pdf', compact('voucher', 'settings', 'logoData', 'hotelPhotoData', 'voucherCss'))
            ->setPaper('a4', 'landscape')
            ->setBasePath(public_path())
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false)
            ->save(storage_path('app/public/'.$path));
        $voucher->update(['pdf_path' => $path]);

        return response()->download(storage_path('app/public/'.$path), $fileName);
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

    private function voucherFont(string $theme): string
    {
        return match ($theme) {
            'modern' => "'Inter', Helvetica, Arial, sans-serif",
            default => "'Playfair Display', Georgia, serif",
        };
    }

    private function textOnColor(string $color): string
    {
        $color = ltrim($color, '#');
        if (strlen($color) !== 6) {
            return '#FFFFFF';
        }

        $rgb = array_map(static fn (string $value): int => hexdec($value), str_split($color, 2));
        $channels = array_map(static function (int $value): float {
            $value /= 255;

            return $value <= 0.03928 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4;
        }, $rgb);
        $luminance = (0.2126 * $channels[0]) + (0.7152 * $channels[1]) + (0.0722 * $channels[2]);

        return $luminance > 0.42 ? '#1A202C' : '#FFFFFF';
    }

    private function imageDataUri(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $mime = Storage::disk('public')->mimeType($path);
        $contents = Storage::disk('public')->get($path);

        return $contents === false || ! $mime ? null : 'data:'.$mime.';base64,'.base64_encode($contents);
    }

    private function resolveOption(string $selected, ?string $other): string
    {
        return $selected === 'Other' && trim((string) $other) !== '' ? trim($other) : $selected;
    }

    private function generateVoucherNumber(): string
    {
        $date = now()->format('Ymd');

        do {
            $number = 'ALMA-HV-'.$date.'-'.random_int(1000, 9999);
        } while (Voucher::where('voucher_number', $number)->exists());

        return $number;
    }
}
