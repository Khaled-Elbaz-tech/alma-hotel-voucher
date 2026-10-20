<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Voucher;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SharedVoucherController extends Controller
{
    private const LAYOUTS = [
        'hotel' => 'vouchers.hotel.partials.voucher-layout',
        'car-rental' => 'vouchers.car-rental.partials.voucher-layout',
        'transfers' => 'vouchers.transfers.partials.voucher-layout',
        'vip-meet-assist' => 'vouchers.vip-meet-assist.partials.voucher-layout',
    ];

    public function show(string $token): View
    {
        $voucher = Voucher::where('share_token', $token)->first();

        abort_if(! $voucher || ! $voucher->shareValid(), 404);

        $settings = CompanySetting::first() ?? new CompanySetting();

        return view('vouchers.shared', [
            'voucher' => $voucher,
            'settings' => $settings,
            'layoutView' => self::LAYOUTS[$voucher->voucher_type] ?? self::LAYOUTS['hotel'],
            'logoData' => $this->storageDataUri($settings->logo_path),
            'hotelPhotoData' => $this->storageDataUri($voucher->hotel_photo_path),
            'heroImageData' => $this->publicDataUri('images/vouchers/'.$voucher->voucher_type.'-hero.jpg'),
        ]);
    }

    private function storageDataUri(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }
        $mime = Storage::disk('public')->mimeType($path) ?: 'image/jpeg';

        return 'data:'.$mime.';base64,'.base64_encode(Storage::disk('public')->get($path));
    }

    private function publicDataUri(string $relative): ?string
    {
        $full = public_path($relative);
        if (! is_file($full)) {
            return null;
        }
        $mime = mime_content_type($full) ?: 'image/jpeg';

        return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($full));
    }
}
