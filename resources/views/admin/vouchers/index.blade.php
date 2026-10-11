@extends('layouts.admin')
@section('page-title', 'Voucher logs')
@section('content')
<div class="admin-card">
    <div class="admin-card-heading"><p class="eyebrow mb-1">Audit trail</p><h2>Generated voucher logs</h2></div>
    <form class="admin-filter mb-3" method="GET" action="{{ route('admin.vouchers.index') }}">
        <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search voucher, guest or hotel…">
        <input class="form-control" name="from" type="date" value="{{ request('from') }}" aria-label="From date">
        <input class="form-control" name="to" type="date" value="{{ request('to') }}" aria-label="To date">
        <button class="btn btn-outline-dark btn-sm" type="submit">Filter</button>
    </form>
    <div class="table-responsive">
        <table class="admin-table">
            <thead><tr><th>Voucher</th><th>Guest</th><th>Hotel</th><th>Stay</th><th>Consultant</th><th>Exports</th><th>Created</th><th></th></tr></thead>
            <tbody>@forelse ($vouchers as $voucher)<tr><td><a class="text-link" href="{{ route('admin.vouchers.show', $voucher) }}"><strong>{{ $voucher->voucher_number }}</strong></a></td><td>{{ $voucher->guest_name }}</td><td>{{ $voucher->hotel_name }}</td><td>{{ $voucher->check_in_date->format('d M') }} – {{ $voucher->check_out_date->format('d M Y') }}<br><span class="muted">{{ $voucher->nights_count }} nights</span></td><td>{{ $voucher->employee?->name ?? '—' }}</td><td>@if ($voucher->pdf_path)<span class="file-tag">PDF</span>@endif @if ($voucher->png_path)<span class="file-tag">PNG</span>@endif @if (!$voucher->pdf_path && !$voucher->png_path)<span class="muted">Not exported</span>@endif</td><td>{{ $voucher->created_at->format('d M Y') }}</td><td><div class="table-actions"><a class="text-link" href="{{ route('vouchers.show', $voucher) }}">Preview</a><form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}">@csrf @method('DELETE')<button class="link-button danger" type="submit">Delete</button></form></div></td></tr>@empty<tr><td colspan="8" class="empty-row">No vouchers found.</td></tr>@endforelse</tbody>
        </table>
    </div>
    <div class="mt-3">{{ $vouchers->links() }}</div>
</div>
@endsection
