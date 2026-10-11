<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $settings->company_name }} Transfers Voucher</title>
    <style>{!! $voucherCss !!}</style>
</head>
<body class="voucher-pdf">
    @include('vouchers.transfers.partials.voucher-layout', [
        'settings' => $settings,
        'voucher' => $voucher,
        'logoData' => $logoData ?? null,
        'hotelPhotoData' => $hotelPhotoData ?? null,
    ])
</body>
</html>
