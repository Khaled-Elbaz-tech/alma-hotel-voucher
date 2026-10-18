@extends('layouts.admin')
@section('page-title', 'Voucher logs')
@section('content')
<div class="admin-card">
    <div class="admin-card-heading"><p class="eyebrow mb-1">Audit trail</p><h2>Generated voucher logs</h2></div>
    <form class="admin-filter mb-3" method="GET" action="{{ route('admin.vouchers.index') }}">
        <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search voucher, guest, company…">
        <select class="form-select" name="type" aria-label="Voucher type" style="max-width: 200px;">
            <option value="">All types</option>
            @foreach ($types as $value => $label)
                <option value="{{ $value }}" {{ request('type') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <input class="form-control" name="from" type="date" value="{{ request('from') }}" aria-label="From date" style="max-width: 170px;">
        <input class="form-control" name="to" type="date" value="{{ request('to') }}" aria-label="To date" style="max-width: 170px;">
        <select class="form-select" name="client_type" aria-label="Client type" style="max-width: 150px;">
            <option value="">Credit/Cash</option>
            <option value="credit" {{ request('client_type') === 'credit' ? 'selected' : '' }}>Credit</option>
            <option value="cash" {{ request('client_type') === 'cash' ? 'selected' : '' }}>Cash</option>
        </select>
        <input class="form-control" name="company" value="{{ request('company') }}" placeholder="Company…" aria-label="Company" style="max-width: 160px;">
        <input class="form-control" name="supplier" value="{{ request('supplier') }}" placeholder="Supplier…" aria-label="Supplier" style="max-width: 160px;">
        <select class="form-select" name="employee" aria-label="Employee" style="max-width: 180px;">
            <option value="">All employees</option>
            @foreach ($employees as $emp)
                <option value="{{ $emp->id }}" {{ (string) request('employee') === (string) $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-dark btn-sm" type="submit">Filter</button>
        @if (request('search') || request('type') || request('from') || request('to') || request('client_type') || request('company') || request('supplier') || request('employee'))
            <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.vouchers.index') }}">Clear</a>
        @endif
    </form>
    <div class="table-responsive">
        <table class="admin-table">
            <thead><tr><th>Voucher</th><th>Type</th><th>Guest</th><th>Client</th><th>Supplier</th><th>Details</th><th>Consultant</th><th>Exports</th><th>Created</th><th></th></tr></thead>
            <tbody>
            @forelse ($vouchers as $voucher)
                <tr>
                    <td><a class="text-link" href="{{ route('admin.vouchers.show', $voucher) }}"><strong>{{ $voucher->voucher_number }}</strong></a></td>
                    <td><span class="file-tag">{{ $voucher->typeLabel() }}</span></td>
                    <td>{{ $voucher->guest_name ?: '—' }}</td>
                    <td>@if($voucher->client_company)<span class="file-tag">{{ $voucher->client_type === 'credit' ? 'Credit' : 'Cash' }}</span> {{ $voucher->client_company }}@else<span class="muted">—</span>@endif</td>
                    <td>{{ $voucher->supplier ?: '—' }}</td>
                    <td>{{ $voucher->summaryLine() }}</td>
                    <td>{{ $voucher->employee?->name ?? '—' }}</td>
                    <td>@if ($voucher->pdf_path)<span class="file-tag">PDF</span>@endif @if ($voucher->png_path)<span class="file-tag">PNG</span>@endif @if (!$voucher->pdf_path && !$voucher->png_path)<span class="muted">Not exported</span>@endif</td>
                    <td>{{ $voucher->created_at?->format('d M Y') ?? '—' }}</td>
                    <td>
                        <div class="table-actions">
                            @if ($voucher->publicShowRoute())
                                <a class="text-link" href="{{ route($voucher->publicShowRoute(), $voucher) }}" target="_blank" rel="noopener">Preview</a>
                            @endif
                            <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}">@csrf @method('DELETE')<button class="link-button danger" type="submit" onclick="return confirm('Delete this voucher log permanently?')">Delete</button></form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" class="empty-row">No vouchers found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $vouchers->links() }}</div>
</div>
@endsection
