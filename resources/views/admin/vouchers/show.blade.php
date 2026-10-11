@extends('layouts.admin')
@section('page-title', 'Voucher log')
@section('content')
<div class="admin-card">
    <div class="admin-card-heading d-flex align-items-center justify-content-between"><div><p class="eyebrow mb-1">Voucher record</p><h2>{{ $voucher->voucher_number }}</h2></div><div class="d-flex gap-2"><a class="btn btn-outline-dark btn-sm" href="{{ route('vouchers.pdf', $voucher) }}">Download PDF</a><a class="btn btn-alma btn-sm" href="{{ route('vouchers.show', $voucher) }}">Open preview</a></div></div>
    <div class="record-grid">
        <div><span>Guest</span><strong>{{ $voucher->guest_name }}</strong></div>
        <div><span>Hotel</span><strong>{{ $voucher->hotel_name }}</strong></div>
        <div><span>Confirmation / PNR</span><strong>{{ $voucher->confirmation_reference }}</strong></div>
        <div><span>Stay</span><strong>{{ $voucher->check_in_date->format('d M Y') }} – {{ $voucher->check_out_date->format('d M Y') }}</strong></div>
        <div><span>Meal plan</span><strong>{{ $voucher->meal_plan }}</strong></div>
        <div><span>Room type</span><strong>{{ $voucher->room_type }}</strong></div>
        <div><span>Consultant</span><strong>{{ $voucher->employee?->name ?? '—' }}</strong></div>
    </div>
    <div class="record-policy"><strong>Cancellation policy</strong><p>{{ $voucher->cancellation_policy }}</p></div>
    <div class="record-files"><strong>Stored exports</strong><div>@if ($voucher->pdf_path)<a href="{{ $voucher->pdfUrl() }}" target="_blank" rel="noopener">Open PDF</a>@else<span class="muted">No PDF generated</span>@endif @if ($voucher->png_path)<a href="{{ $voucher->pngUrl() }}" target="_blank" rel="noopener">Open PNG</a>@else<span class="muted">No PNG generated</span>@endif</div></div>
    <div class="form-actions admin-form-actions"><a class="btn btn-outline-dark" href="{{ route('admin.vouchers.index') }}">Back to logs</a><form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}">@csrf @method('DELETE')<button class="btn btn-outline-danger" type="submit">Delete log</button></form></div>
</div>
@endsection
