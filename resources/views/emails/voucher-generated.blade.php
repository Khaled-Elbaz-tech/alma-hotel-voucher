<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #8B0000;">Alma Travel Voucher</h2>
    <p>Dear {{ $voucher->user->name ?? 'Guest' }},</p>
    <p>Your voucher <strong>{{ $voucher->voucher_number }}</strong> has been generated successfully.</p>
    <p>Please find the voucher attached as a PDF.</p>
    <br>
    <p>Best regards,<br><strong>Alma Travel</strong><br><em>We Always Care</em></p>
</body>
</html>
