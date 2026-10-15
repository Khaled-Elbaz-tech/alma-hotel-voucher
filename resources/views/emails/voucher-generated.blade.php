<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; background: #f0ede8; margin: 0; padding: 20px;">
<div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.12);">

    <!-- Header -->
    <div style="background: linear-gradient(135deg, #5F1812 0%, #8B251E 60%, #A93226 100%); padding: 35px 40px; text-align: center;">
        <div style="font-size: 28px; font-weight: 700; color: #ffffff; letter-spacing: 3px;">ALMA TRAVEL</div>
        <div style="font-size: 11px; color: #D4AF37; letter-spacing: 3px; margin-top: 6px; text-transform: uppercase;">More Than A Journey</div>
        <div style="width: 50px; height: 2px; background: #D4AF37; margin: 15px auto 0;"></div>
    </div>

    <!-- Body -->
    <div style="padding: 35px 40px;">
        <div style="font-size: 22px; color: #1a1a1a; font-weight: 600; margin-bottom: 8px;">Your Voucher is Ready ✈️</div>
        <p style="color: #555; font-size: 15px; line-height: 1.7; margin-bottom: 25px;">
            Dear <strong>{{ $voucher->user->name ?? 'Valued Guest' }}</strong>,<br>
            Your voucher <strong style="color: #8B251E;">{{ $voucher->voucher_number }}</strong> has been generated successfully and is attached to this email as a PDF document.
        </p>

        <!-- Voucher Details Card -->
        <div style="background: #faf8f5; border: 1px solid #e8e0d0; border-radius: 10px; padding: 25px; margin-bottom: 25px;">
            <div style="font-size: 12px; font-weight: 700; color: #8B251E; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 2px solid #D4AF37;">📋 Voucher Details</div>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 10px 0; font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #f0ebe0;">Voucher Number</td>
                    <td style="padding: 10px 0; font-size: 16px; font-weight: 700; color: #8B251E; text-align: right; border-bottom: 1px solid #f0ebe0;">{{ $voucher->voucher_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #f0ebe0;">Guest Name</td>
                    <td style="padding: 10px 0; font-size: 14px; font-weight: 600; color: #1a1a1a; text-align: right; border-bottom: 1px solid #f0ebe0;">{{ $voucher->guest_name ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 1px;">Issued On</td>
                    <td style="padding: 10px 0; font-size: 14px; font-weight: 600; color: #1a1a1a; text-align: right;">{{ $voucher->created_at->format('d M Y, h:i A') }}</td>
                </tr>
            </table>
        </div>

        <div style="background: #fef9e7; border-right: 3px solid #D4AF37; padding: 15px 18px; border-radius: 0 8px 8px 0; font-size: 14px; color: #665; line-height: 1.6; margin-bottom: 10px;">
            ✅ Please present this voucher upon arrival. If you have any questions or need assistance, our team is ready to help.
        </div>
    </div>

    <!-- Footer -->
    <div style="background: #1a1a1a; padding: 30px 40px; text-align: center;">
        <div style="color: #ffffff; font-size: 15px; font-weight: 600; margin-bottom: 5px;">Best regards,</div>
        <div style="color: #D4AF37; font-size: 14px; font-weight: 700; margin-bottom: 18px;">Alma Travel Team</div>
        <div style="font-size: 12px; color: rgba(255,255,255,0.75);">
            📞 <a href="tel:+966920022050" style="color: #D4AF37; text-decoration: none;">+966 920022050</a>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            ✉️ <a href="mailto:info@almatravel.co" style="color: #D4AF37; text-decoration: none;">info@almatravel.co</a>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            🌐 <a href="https://www.almatravel.co" style="color: #D4AF37; text-decoration: none;">www.almatravel.co</a>
        </div>
        <div style="margin-top: 18px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 11px; color: rgba(255,255,255,0.4);">© 2026 Alma Travel. All rights reserved.</div>
    </div>

</div>
</body>
</html>
