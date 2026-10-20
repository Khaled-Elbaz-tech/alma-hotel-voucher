@php
    // Embed images into the email itself (CID) so they display in every
    // mail client without hosted URLs or a storage symlink.
    $heroSrc = null;
    $heroFile = public_path('images/vouchers/'.$voucher->voucher_type.'-hero.jpg');
    if (is_file($heroFile)) {
        $heroSrc = $message->embed($heroFile);
    }

    $photoSrc = null;
    if ($voucher->hotel_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($voucher->hotel_photo_path)) {
        $photoSrc = $message->embed(storage_path('app/public/'.$voucher->hotel_photo_path));
    }

    $logoSrc = null;
    if (!empty($settings?->logo_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings->logo_path)) {
        $logoSrc = $message->embed(storage_path('app/public/'.$settings->logo_path));
    }
@endphp
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alma Travel — Voucher {{ $voucher->voucher_number }}</title>
</head>
<body style="margin: 0; padding: 24px; background-color: #eceae6;">
@include($layoutView, [
    'voucher' => $voucher,
    'settings' => $settings,
    'preview' => false,
    'logoData' => $logoSrc,
    'hotelPhotoData' => $photoSrc,
    'heroImageData' => $heroSrc,
])
</body>
</html>
