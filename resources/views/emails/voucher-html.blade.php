<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="margin: 0; padding: 20px; background: #f5f5f5; font-family: Arial, sans-serif;">
<div style="max-width: 800px; margin: 0 auto; background: white; padding: 20px;">
<p style="color: #333;">Dear {{ $voucher->user->name ?? 'Guest' }},</p>
<p style="color: #333;">Your voucher <strong>{{ $voucher->voucher_number }}</strong> is ready:</p>
</div>
<br>
<!-- Voucher HTML embedded directly -->
<div style="max-width: 1000px; margin: 0 auto;">
{!! $voucherHtml !!}
</div>
<br>
<div style="max-width: 800px; margin: 0 auto; background: white; padding: 20px; text-align: center; color: #888; font-size: 12px;">
<p>Alma Travel | +966 920022050 | info@almatravel.co</p>
</div>
</body>
</html>
