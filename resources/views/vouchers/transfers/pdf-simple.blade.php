<!DOCTYPE html><html><head><meta charset="utf-8"><style>
@page { margin: 0; }
body { font-family: DejaVu Sans, sans-serif; margin: 0; padding: 0; color: #333; font-size: 10px; }
.header { background: #0B3B3E; color: white; padding: 25px 30px; }
.header-table { width: 100%; }
.header-table td { vertical-align: middle; }
.brand { font-size: 20px; font-weight: bold; color: #C8A24B; }
.tagline { font-size: 9px; color: #C8A24B; letter-spacing: 2px; margin-top: 3px; }
.voucher-title { text-align: center; font-size: 13px; color: #C8A24B; letter-spacing: 2px; font-weight: bold; }
.voucher-no { text-align: right; font-size: 9px; color: #ccc; }
.content { padding: 20px 30px; }
.section { margin-bottom: 15px; }
.section-head { background: #0B3B3E; color: #C8A24B; padding: 6px 12px; font-size: 10px; font-weight: bold; letter-spacing: 1px; }
.info-table { width: 100%; border-collapse: collapse; }
.info-table td { padding: 7px 12px; border-bottom: 1px solid #e8e2d8; font-size: 10px; }
.info-table .label { width: 32%; color: #888; font-size: 9px; background: #faf8f3; font-weight: bold; }
.footer { background: #0B3B3E; color: #C8A24B; padding: 15px 30px; font-size: 8px; text-align: center; }
.terms { font-size: 9px; color: #666; padding: 10px 12px; line-height: 1.5; }
.badge-yes { background: #d4edda; color: #155724; padding: 2px 8px; font-weight: bold; font-size: 9px; }
.badge-no { background: #f8d7da; color: #721c24; padding: 2px 8px; font-weight: bold; font-size: 9px; }
</style></head><body>
<div class="header">
<table class="header-table"><tr>
<td style="width: 30%;"><div class="brand">Alma Travel</div><div class="tagline">YOUR JOURNEY, OUR COMMITMENT</div></td>
<td style="width: 40%;"><div class="voucher-title">TRANSFER<br>VOUCHER</div></td>
<td style="width: 30%;"><div class="voucher-no">VOUCHER NO.<br><strong style="color: white;">{{ $voucher->voucher_number }}</strong></div></td>
</tr></table>
</div>
<div class="content">
<div class="section"><div class="section-head">GUEST INFORMATION</div>
<table class="info-table">
<tr><td class="label">GUEST NAME</td><td><strong>{{ $voucher->guest_name }}</strong></td></tr>
<tr><td class="label">MOBILE</td><td>{{ $voucher->guest_mobile }}</td></tr>
<tr><td class="label">CONFIRMATION REF</td><td>{{ $voucher->confirmation_reference }}</td></tr>
<tr><td class="label">TRIP TYPE</td><td>{{ strtoupper(str_replace('_', ' ', $voucher->trip_type)) }}</td></tr>
</table></div>
<div class="section"><div class="section-head">TRANSFER DETAILS</div>
<table class="info-table">
<tr><td class="label">PICKUP</td><td>{{ $voucher->pickup_location }}<br>{{ $voucher->pickup_date }} {{ $voucher->pickup_time }}</td></tr>
<tr><td class="label">DROPOFF</td><td>{{ $voucher->dropoff_location }}</td></tr>
<tr><td class="label">VEHICLE</td><td>{{ $voucher->vehicle_type }}</td></tr>
<tr><td class="label">PASSENGERS</td><td>{{ $voucher->passengers }}</td></tr>
@if($voucher->flight_number)<tr><td class="label">FLIGHT</td><td>{{ $voucher->flight_number }} - {{ $voucher->flight_date }} {{ $voucher->flight_time }}</td></tr>@endif
</table></div>
@if($voucher->trip_type == 'round_trip')
<div class="section"><div class="section-head">RETURN TRANSFER</div>
<table class="info-table">
<tr><td class="label">PICKUP</td><td>{{ $voucher->return_pickup_location }}<br>{{ $voucher->return_pickup_date }} {{ $voucher->return_pickup_time }}</td></tr>
<tr><td class="label">DROPOFF</td><td>{{ $voucher->return_dropoff_location }}</td></tr>
</table></div>
@endif
</div><div class="footer">
<table style="width: 100%;"><tr>
<td style="text-align: left; color: #C8A24B;">ALMA TRAVEL CONTACT<br>+966 920022050<br>info@almatravel.co</td>
<td style="text-align: right; color: #C8A24B;"><em style="font-size: 12px;">With Compliments</em><br>Alma Travel</td>
</tr></table>
</div></body></html>