@extends('layouts.admin')
@section('page-title', 'Dashboard')
@section('content')
<div class="admin-stats">
    <div class="stat-card"><span>Generated vouchers</span><strong>{{ $voucherCount }}</strong><a href="{{ route('admin.vouchers.index') }}">View logs →</a></div>
    <div class="stat-card"><span>Active employees</span><strong>{{ $activeEmployeeCount }}</strong><a href="{{ route('admin.employees.index') }}">Manage people →</a></div>
    <div class="stat-card"><span>Total employees</span><strong>{{ $employeeCount }}</strong><a href="{{ route('admin.employees.index') }}">View directory →</a></div>
</div>
<div class="admin-stats">
    <div class="stat-card"><span>Vouchers this month</span><strong>{{ $vouchersThisMonth }}</strong></div>
    <div class="stat-card"><span>Credit vouchers</span><strong>{{ $creditCount }}</strong></div>
    <div class="stat-card"><span>Cash vouchers</span><strong>{{ $cashCount }}</strong></div>
</div>
<div class="admin-card">
    <div class="admin-card-heading"><div><p class="eyebrow mb-1">Statistics</p><h2>Voucher activity</h2></div></div>
    <div class="row g-4">
        <div class="col-md-5">
            <canvas id="typeChart" height="220"></canvas>
        </div>
        <div class="col-md-7">
            <canvas id="monthChart" height="220"></canvas>
        </div>
    </div>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <h3 class="h6 fw-bold">Top consultants</h3>
            @forelse($topEmployees as $row)
                <div class="d-flex justify-content-between border-bottom py-1"><span>{{ $row->employee?->name ?? '—' }}</span><strong>{{ $row->c }}</strong></div>
            @empty
                <p class="text-muted small mb-0">No data yet.</p>
            @endforelse
        </div>
        <div class="col-md-6">
            <h3 class="h6 fw-bold">Top credit companies</h3>
            @forelse($topCompanies as $row)
                <div class="d-flex justify-content-between border-bottom py-1"><span>{{ $row->client_company }}</span><strong>{{ $row->c }}</strong></div>
            @empty
                <p class="text-muted small mb-0">No data yet.</p>
            @endforelse
        </div>
    </div>
</div>
<div class="admin-card">
    <div class="admin-card-heading d-flex align-items-center justify-content-between"><div><p class="eyebrow mb-1">Recent activity</p><h2>Latest voucher logs</h2></div><a class="text-link" href="{{ route('admin.vouchers.index') }}">View all</a></div>
    @if ($recentVouchers->isEmpty())
        <div class="empty-state"><strong>No vouchers yet</strong><span>Generate a voucher from the consultant workspace to see it here.</span><a class="btn btn-alma btn-sm" href="{{ route('home') }}">Open generator</a></div>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th>Voucher</th><th>Type</th><th>Guest</th><th>Details</th><th>Consultant</th><th>Created</th><th></th></tr></thead>
                <tbody>@foreach ($recentVouchers as $voucher)<tr><td><strong>{{ $voucher->voucher_number }}</strong></td><td><span class="file-tag">{{ $voucher->typeLabel() }}</span></td><td>{{ $voucher->guest_name ?: '—' }}</td><td>{{ $voucher->summaryLine() }}</td><td>{{ $voucher->employee?->name ?? '—' }}</td><td>{{ $voucher->created_at?->format('d M Y') ?? '—' }}</td><td><a class="text-link" href="{{ route('admin.vouchers.show', $voucher) }}">Open</a></td></tr>@endforeach</tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const typeLabels = { 'hotel': 'Hotel', 'car-rental': 'Car Rental', 'transfers': 'Transfers', 'vip-meet-assist': 'VIP Meet & Assist' };
    const byType = @json($byType);
    const monthly = @json($monthly);
    const palette = ['#8B251E', '#C0392B', '#64748B', '#A93226', '#334155', '#94A3B8'];
    new Chart(document.getElementById('typeChart'), {
        type: 'doughnut',
        data: { labels: Object.keys(byType).map(k => typeLabels[k] || k), datasets: [{ data: Object.values(byType), backgroundColor: palette }] },
        options: { plugins: { legend: { position: 'bottom' } } }
    });
    new Chart(document.getElementById('monthChart'), {
        type: 'bar',
        data: { labels: monthly.map(m => m.label), datasets: [{ label: 'Vouchers', data: monthly.map(m => m.count), backgroundColor: '#8B251E', borderRadius: 6 }] },
        options: { scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }, plugins: { legend: { display: false } } }
    });
</script>
@endsection
