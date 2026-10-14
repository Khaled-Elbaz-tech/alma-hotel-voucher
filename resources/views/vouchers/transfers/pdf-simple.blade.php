<!DOCTYPE html>
<html><head><meta charset="utf-8">
<style>
body { font-family: DejaVu Sans, sans-serif; margin: 0; padding: 20px; color: #333; font-size: 11px; }
.header { background: #0B3B3E; color: white; padding: 20px; text-align: center; margin-bottom: 20px; }
.header h1 { margin: 0; font-size: 18px; color: #C8A24B; }
table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
th { background: #f5f5f5; text-align: left; padding: 8px; font-size: 10px; color: #666; width: 30%; }
td { padding: 8px; border-bottom: 1px solid #eee; }
.section-title { background: #0B3B3E; color: #C8A24B; padding: 8px 12px; font-size: 12px; font-weight: bold; margin: 15px 0 0; }
.footer { margin-top: 20px; padding-top: 15px; border-top: 2px solid #C8A24B; font-size: 9px; color: #666; text-align: center; }
</style></head><body>
<div class="header"><h1>ALMA TRAVEL</h1><p>We Always Care</p>
<h2 style="color: white; margin: 10px 0 0; font-size: 14px;">TRANSFER VOUCHER ({{ strtoupper(str_replace('_', ' ', $voucher->trip_type)) }})</h2>
<p>Voucher No: {{ $voucher->voucher_number }}</p></div>
<div class="section-title">GUEST INFORMATION</div>
<table>
<tr><th>Guest Name</th><td>{{ $voucher->guest_name }}</td></tr>
<tr><th>Mobile</th><td>{{ $voucher->guest_mobile }}</td></tr>
<tr><th>Confirmation Ref</th><td>{{ $voucher->confirmation_reference }}</td></tr>
</table>
<div class="section-title">TRANSFER DETAILS</div>
<table>
<tr><th>Pickup</th><td>{{ $voucher->pickup_location }} - {{ $voucher->pickup_date }} {{ $voucher->pickup_time }}</td></tr>
<tr><th>Dropoff</th><td>{{ $voucher->dropoff_location }}</td></tr>
<tr><th>Vehicle</th><td>{{ $voucher->vehicle_type }}</td></tr>
<tr><th>Passengers</th><td>{{ $voucher->passengers }}</td></tr>
@if($voucher->flight_number)<tr><th>Flight</th><td>{{ $voucher->flight_number }} - {{ $voucher->flight_date }} {{ $voucher->flight_time }}</td></tr>@endif
</table>
@if($voucher->trip_type == 'round_trip')
<div class="section-title">RETURN TRANSFER</div>
<table>
<tr><th>Pickup</th><td>{{ $voucher->return_pickup_location }} - {{ $voucher->return_pickup_date }} {{ $voucher->return_pickup_time }}</td></tr>
<tr><th>Dropoff</th><td>{{ $voucher->return_dropoff_location }}</td></tr>
</table>
@endif
<div class="footer"><p>Alma Travel | +966 920022050 | info@almatravel.co</p></div>
</body></html>
