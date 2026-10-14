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
<h2 style="color: white; margin: 10px 0 0; font-size: 14px;">CAR RENTAL VOUCHER</h2>
<p>Voucher No: {{ $voucher->voucher_number }}</p></div>
<div class="section-title">GUEST INFORMATION</div>
<table>
<tr><th>Guest Name</th><td>{{ $voucher->guest_name }}</td></tr>
<tr><th>Confirmation Ref</th><td>{{ $voucher->confirmation_reference }}</td></tr>
</table>
<div class="section-title">RENTAL DETAILS</div>
<table>
<tr><th>Vehicle</th><td>{{ $voucher->vehicle_type }}</td></tr>
<tr><th>Pickup Date</th><td>{{ $voucher->pickup_date }}</td></tr>
<tr><th>Return Date</th><td>{{ $voucher->dropoff_date }}</td></tr>
<tr><th>Duration</th><td>{{ $voucher->rental_duration }} days</td></tr>
</table>
@if($voucher->special_requests)<div class="section-title">NOTES</div><table><tr><td>{{ $voucher->special_requests }}</td></tr></table>@endif
<div class="footer"><p>Alma Travel | +966 920022050 | info@almatravel.co</p></div>
</body></html>
