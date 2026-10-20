<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alma Travel — Voucher {{ $voucher->voucher_number }}</title>
<style>
    body { margin: 0; background: #eceae6; font-family: Arial, Helvetica, sans-serif; }
    .share-bar { background: #8B251E; color: #fff; padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
    .share-bar .brand { font-size: 18px; font-weight: 700; letter-spacing: .5px; }
    .share-bar .brand small { display: block; font-size: 9px; letter-spacing: 3px; font-weight: 400; color: #f3d9d5; }
    .share-actions { display: flex; align-items: center; gap: 14px; }
    .share-actions button { background: #fff; color: #8B251E; border: 0; border-radius: 8px; padding: 9px 18px; font-weight: 700; cursor: pointer; font-size: 13px; }
    #shareQr { background: #fff; padding: 6px; border-radius: 8px; line-height: 0; }
    .share-wrap { max-width: 1060px; margin: 22px auto; padding: 0 14px; }
    .share-note { text-align: center; color: #777; font-size: 12px; margin: 16px 0 30px; }
    @media print { .share-bar, .share-note { display: none !important; } body { background: #fff; } .share-wrap { margin: 0; padding: 0; max-width: none; } }
</style>
</head>
<body>
<div class="share-bar">
    <div class="brand">Alma Travel<small>MORE THAN A JOURNEY</small></div>
    <div class="share-actions">
        <div id="shareQr" title="Scan to open this voucher"></div>
        <button type="button" onclick="window.print()">🖨 Print voucher</button>
    </div>
</div>
<div class="share-wrap" id="printVoucher">
    @include($layoutView, ['voucher' => $voucher, 'settings' => $settings, 'preview' => false, 'logoData' => $logoData, 'hotelPhotoData' => $hotelPhotoData, 'heroImageData' => $heroImageData])
</div>
<p class="share-note">This voucher was shared securely by Alma Travel. Link valid until {{ $voucher->share_expires_at?->format('d M Y') }}.</p>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    if (window.QRCode) { new QRCode(document.getElementById('shareQr'), { text: window.location.href, width: 64, height: 64, correctLevel: QRCode.CorrectLevel.M }); }
</script>
</body>
</html>
