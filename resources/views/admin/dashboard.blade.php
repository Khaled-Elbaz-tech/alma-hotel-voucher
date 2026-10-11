@extends('layouts.admin')
@section('page-title', 'Dashboard')
@section('content')
<div class="admin-stats">
    <div class="stat-card"><span>Generated vouchers</span><strong>{{ $voucherCount }}</strong><a href="{{ route('admin.vouchers.index') }}">View logs →</a></div>
    <div class="stat-card"><span>Active employees</span><strong>{{ $activeEmployeeCount }}</strong><a href="{{ route('admin.employees.index') }}">Manage people →</a></div>
    <div class="stat-card"><span>Total employees</span><strong>{{ $employeeCount }}</strong><a href="{{ route('admin.employees.index') }}">View directory →</a></div>
</div>
<div class="admin-card">
    <div class="admin-card-heading d-flex align-items-center justify-content-between"><div><p class="eyebrow mb-1">Recent activity</p><h2>Latest voucher logs</h2></div><a class="text-link" href="{{ route('admin.vouchers.index') }}">View all</a></div>
    @if ($recentVouchers->isEmpty())
        <div class="empty-state"><strong>No vouchers yet</strong><span>Generate a voucher from the consultant workspace to see it here.</span><a class="btn btn-alma btn-sm" href="{{ route('home') }}">Open generator</a></div>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th>Voucher</th><th>Guest</th><th>Hotel</th><th>Consultant</th><th>Created</th><th></th></tr></thead>
                <tbody>@foreach ($recentVouchers as $voucher)<tr><td><strong>{{ $voucher->voucher_number }}</strong></td><td>{{ $voucher->guest_name }}</td><td>{{ $voucher->hotel_name }}</td><td>{{ $voucher->employee?->name ?? '—' }}</td><td>{{ $voucher->created_at->format('d M Y') }}</td><td><a class="text-link" href="{{ route('admin.vouchers.show', $voucher) }}">Open</a></td></tr>@endforeach</tbody>
            </table>
        </div>
    @endif
</div>
@endsection
