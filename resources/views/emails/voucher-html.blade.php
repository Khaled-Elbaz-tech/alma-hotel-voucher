<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="margin: 0; padding: 0; background-color: #f4f1ea; font-family: Arial, Helvetica, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f1ea; padding: 20px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; max-width: 600px;">
<!-- Header -->
<tr><td style="background-color: #0B3B3E; padding: 25px 30px; text-align: center;">
<div style="color: #C8A24B; font-size: 22px; font-weight: bold; letter-spacing: 1px;">Alma Travel</div>
<div style="color: #C8A24B; font-size: 9px; letter-spacing: 3px; margin-top: 5px;">YOUR JOURNEY, OUR COMMITMENT</div>
<div style="color: #C8A24B; font-size: 13px; letter-spacing: 2px; margin-top: 15px; font-weight: bold;">{{ strtoupper(str_replace('-', ' ', $voucher->voucher_type)) }} VOUCHER</div>
<div style="color: #ffffff; font-size: 11px; margin-top: 8px;">Voucher No: <strong>{{ $voucher->voucher_number }}</strong></div>
</td></tr>
<!-- Greeting -->
<tr><td style="padding: 20px 30px; color: #333333; font-size: 13px;">
<p>Dear {{ $voucher->user->name ?? 'Guest' }},</p>
<p>Your voucher is ready. Details below:</p>
</td></tr>
<!-- Voucher Details -->
<tr><td style="padding: 0 30px 20px;">
<table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; font-size: 12px;">
<tr style="background-color: #0B3B3E;"><td colspan="2" style="color: #C8A24B; font-weight: bold; font-size: 11px; letter-spacing: 1px;">BOOKING DETAILS</td></tr>
<tr><td width="35%" style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>GUEST NAME</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->guest_name }}</td></tr>
<tr><td style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>CONFIRMATION REF</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->confirmation_reference }}</td></tr>
@if($voucher->voucher_type == 'hotel')
<tr><td style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>HOTEL</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->hotel_name }}</td></tr>
<tr><td style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>CHECK-IN</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->check_in_date }} {{ $voucher->check_in_time }}</td></tr>
<tr><td style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>CHECK-OUT</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->check_out_date }} {{ $voucher->check_out_time }}</td></tr>
<tr><td style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>ROOM</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->room_type }} - {{ $voucher->meal_plan }}</td></tr>
@elseif($voucher->voucher_type == 'car-rental')
<tr><td style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>VEHICLE</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->vehicle_type }}</td></tr>
<tr><td style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>PICKUP</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->pickup_date }}</td></tr>
<tr><td style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>RETURN</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->dropoff_date }}</td></tr>
@elseif($voucher->voucher_type == 'transfers')
<tr><td style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>FROM</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->pickup_location }}</td></tr>
<tr><td style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>TO</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->dropoff_location }}</td></tr>
<tr><td style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>DATE/TIME</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->pickup_date }} {{ $voucher->pickup_time }}</td></tr>
<tr><td style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>VEHICLE</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->vehicle_type }} ({{ $voucher->passengers }} pax)</td></tr>
@elseif($voucher->voucher_type == 'vip-meet-assist')
<tr><td style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>DATE/TIME</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->service_date }} {{ $voucher->service_time }}</td></tr>
<tr><td style="background-color: #faf8f3; color: #888888; border-bottom: 1px solid #e8e2d8;"><strong>MEETING POINT</strong></td><td style="border-bottom: 1px solid #e8e2d8;">{{ $voucher->meeting_point }}</td></tr>
@endif
</table>
</td></tr>
<!-- Footer -->
<tr><td style="background-color: #0B3B3E; padding: 20px 30px; text-align: center;">
<div style="color: #C8A24B; font-size: 11px;"><em>With Compliments</em></div>
<div style="color: #C8A24B; font-size: 10px; margin-top: 8px;">Alma Travel | +966 920022050 | info@almatravel.co</div>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>
