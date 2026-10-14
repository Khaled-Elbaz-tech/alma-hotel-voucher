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
.badge { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 10px; font-weight: bold; }
.yes { background: #d4edda; color: #155724; }
.no { background: #f8d7da; color: #721c24; }
</style></head><body>
<div class="header"><h1>ALMA TRAVEL</h1><p>We Always Care</p>
<h2 style="color: white; margin: 10px 0 0; font-size: 14px;">VIP MEET & ASSIST VOUCHER</h2>
<p>Voucher No: {{ $voucher->voucher_number }}</p></div>
<div class="section-title">GUEST INFORMATION</div>
<table>
<tr><th>Guest Name</th><td>{{ $voucher->guest_name }}</td></tr>
<tr><th>Confirmation Ref</th><td>{{ $voucher->confirmation_reference }}</td></tr>
<tr><th>Service Date</th><td>{{ $voucher->service_date }} {{ $voucher->service_time }}</td></tr>
<tr><th>Meeting Point</th><td>{{ $voucher->meeting_point }}</td></tr>
@if($voucher->flight_number)<tr><th>Flight</th><td>{{ $voucher->flight_number }}</td></tr>@endif
</table>
<div class="section-title">SERVICES</div>
<table>
<tr><th>Meet & Assist</th><td><span class="badge {{ $voucher->service_meet_assist ? 'yes' : 'no' }}">{{ $voucher->service_meet_assist ? 'YES' : 'NO' }}</span></td></tr>
<tr><th>Private Car</th><td><span class="badge {{ $voucher->service_private_car ? 'yes' : 'no' }}">{{ $voucher->service_private_car ? 'YES' : 'NO' }}</span></td></tr>
<tr><th>Fast Track</th><td><span class="badge {{ $voucher->fast_track ? 'yes' : 'no' }}">{{ $voucher->fast_track ? 'YES' : 'NO' }}</span></td></tr>
<tr><th>VIP Lounge</th><td><span class="badge {{ $voucher->vip_lounge_access ? 'yes' : 'no' }}">{{ $voucher->vip_lounge_access ? 'YES' : 'NO' }}</span></td></tr>
<tr><th>Immigration</th><td><span class="badge {{ $voucher->service_immigration ? 'yes' : 'no' }}">{{ $voucher->service_immigration ? 'YES' : 'NO' }}</span></td></tr>
</table>
<div class="footer"><p>Alma Travel | +966 920022050 | info@almatravel.co</p></div>
</body></html>
