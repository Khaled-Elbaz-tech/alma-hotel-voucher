<!DOCTYPE html>
<html><head><meta charset="utf-8">
<style>
@page { margin: 0; }
body { font-family: DejaVu Sans, sans-serif; margin: 0; padding: 0; color: #333; font-size: 10px; }
.header { background: #0B3B3E; color: white; padding: 25px 30px; }
.header-table { width: 100%; }
.header-table td { vertical-align: middle; }
.brand { font-size: 20px; font-weight: bold; color: #C8A24B; }
.tagline { font-size: 9px; color: #C8A24B; letter-spacing: 2px; margin-top: 3px; }
.voucher-title { text-align: center; font-size: 14px; color: #C8A24B; letter-spacing: 3px; font-weight: bold; }
.voucher-no { text-align: right; font-size: 9px; color: #ccc; }
.content { padding: 20px 30px; }
.section { margin-bottom: 15px; }
.section-head { background: #0B3B3E; color: #C8A24B; padding: 6px 12px; font-size: 10px; font-weight: bold; letter-spacing: 1px; }
.info-table { width: 100%; border-collapse: collapse; }
.info-table td { padding: 7px 12px; border-bottom: 1px solid #e8e2d8; font-size: 10px; }
.info-table .label { width: 32%; color: #888; font-size: 9px; background: #faf8f3; font-weight: bold; }
.photo-box { text-align: center; padding: 15px; background: #faf8f3; border: 1px solid #e8e2d8; margin-bottom: 15px; color: #999; font-size: 10px; }
.footer { background: #0B3B3E; color: #C8A24B; padding: 15px 30px; font-size: 8px; text-align: center; }
.footer td { color: #C8A24B; font-size: 8px; padding: 3px; }
.terms { font-size: 9px; color: #666; padding: 10px 12px; line-height: 1.5; }
</style></head><body>
<div class="header">
<table class="header-table"><tr>
<td style="width: 30%;"><div class="brand">Alma Travel</div><div class="tagline">WE ALWAYS CARE</div></td>
<td style="width: 40%;"><div class="voucher-title">HOTEL CONFIRMATION<br>VOUCHER</div></td>
<td style="width: 30%;"><div class="voucher-no">VOUCHER NO.<br><strong style="color: white;">{{ $voucher->voucher_number }}</strong></div></td>
</tr></table>
</div>
<div class="content">
<div class="section">
<div class="section-head">BOOKING DETAILS</div>
<table class="info-table">
<tr><td class="label">GUEST NAME</td><td><strong>{{ $voucher->guest_name }}</strong></td></tr>
<tr><td class="label">CONFIRMATION REF</td><td>{{ $voucher->confirmation_reference }}</td></tr>
<tr><td class="label">HOTEL</td><td><strong>{{ $voucher->hotel_name }}</strong></td></tr>
<tr><td class="label">ADDRESS</td><td>{{ $voucher->hotel_address }}</td></tr>
</table>
</div>
<div class="section">
<div class="section-head">STAY INFORMATION</div>
<table class="info-table">
<tr><td class="label">CHECK-IN</td><td>{{ $voucher->check_in_date }} at {{ $voucher->check_in_time }}</td></tr>
<tr><td class="label">CHECK-OUT</td><td>{{ $voucher->check_out_date }} at {{ $voucher->check_out_time }}</td></tr>
<tr><td class="label">NIGHTS</td><td>{{ $voucher->nights_count }}</td></tr>
<tr><td class="label">ROOM TYPE</td><td>{{ $voucher->room_type }}</td></tr>
<tr><td class="label">MEAL PLAN</td><td>{{ $voucher->meal_plan }}</td></tr>
</table>
</div>
@if($voucher->special_requests)
<div class="section">
<div class="section-head">SPECIAL REQUESTS</div>
<div class="terms">{{ $voucher->special_requests }}</div>
</div>
@endif
<div class="section">
<div class="section-head">CANCELLATION POLICY</div>
<div class="terms">{{ $voucher->cancellation_policy }}</div>
</div>
</div>
<div class="footer">
<table style="width: 100%;"><tr>
<td style="text-align: left;">ALMA TRAVEL CONTACT<br>+966 920022050<br>info@almatravel.co</td>
<td style="text-align: right;"><em style="font-size: 12px;">With Compliments</em><br>Alma Travel</td>
</tr></table>
</div>
</body></html>
