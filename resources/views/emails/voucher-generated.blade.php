<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto;">
    <div style="background: linear-gradient(135deg, #1a1512 0%, #2d1f1a 100%); padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="color: #c9a227; margin: 0; font-size: 24px; letter-spacing: 2px;">ALMA TRAVEL</h1>
        <p style="color: rgba(255,255,255,.7); margin: 8px 0 0; font-size: 13px; letter-spacing: 1px;">We Always Care</p>
    </div>
    <div style="background: #ffffff; padding: 30px; border: 1px solid #e8e2d8; border-top: none; border-radius: 0 0 8px 8px;">
        <h2 style="color: #1a1a1a; margin-top: 0;">Your Voucher is Ready ✈️</h2>
        <p>Dear <strong>{{ $voucher->user->name ?? 'Valued Guest' }}</strong>,</p>
        <p>Your voucher <strong style="color: #8B0000;">{{ $voucher->voucher_number }}</strong> has been generated successfully and is attached to this email as a PDF.</p>
        <table style="width: 100%; margin: 20px 0; border-collapse: collapse;">
            <tr><td style="padding: 8px 0; color: #888; font-size: 13px;">VOUCHER NUMBER</td><td style="padding: 8px 0; font-weight: 600;">{{ $voucher->voucher_number }}</td></tr>
            <tr><td style="padding: 8px 0; color: #888; font-size: 13px;">GUEST NAME</td><td style="padding: 8px 0; font-weight: 600;">{{ $voucher->guest_name ?? '—' }}</td></tr>
            <tr><td style="padding: 8px 0; color: #888; font-size: 13px;">ISSUED ON</td><td style="padding: 8px 0; font-weight: 600;">{{ $voucher->created_at->format('d M Y, h:i A') }}</td></tr>
        </table>
        <p style="color: #666; font-size: 14px;">Please present this voucher upon arrival. If you have any questions, feel free to contact us.</p>
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
            <p style="margin: 0; font-weight: 600;">Best regards,</p>
            <p style="margin: 4px 0 0; color: #8B0000; font-weight: 700;">Alma Travel Team</p>
            <p style="margin: 4px 0 0; font-size: 12px; color: #999;">📞 +966 920022050 &nbsp;|&nbsp; ✉️ info@almatravel.co &nbsp;|&nbsp; 🌐 www.almatravel.co</p>
        </div>
    </div>
</body>
</html>
