@extends('layouts.admin')
@section('page-title', 'Company Reports')
@section('content')
<div class="admin-card">
    <div class="admin-card-heading">
        <div>
            <p class="eyebrow mb-1">Monthly statement</p>
            <h2>Credit company vouchers by month issued</h2>
        </div>
    </div>
    <form method="GET" action="{{ route('admin.reports.index') }}" class="d-flex flex-wrap gap-2 align-items-end mb-3">
        <div>
            <label class="form-label small fw-bold">Month</label>
            <input type="month" name="month" class="form-control" value="{{ $month }}">
        </div>
        <div>
            <label class="form-label small fw-bold">Company</label>
            <select name="company" class="form-select">
                <option value="">All companies</option>
                @foreach($companies as $c)
                    <option value="{{ $c }}" @selected($company === $c)>{{ $c }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label small fw-bold">Type</label>
            <select name="type" class="form-select">
                <option value="">All types</option>
                @foreach($types as $key => $label)
                    <option value="{{ $key }}" @selected($type === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-alma" type="submit">Show</button>
        <a class="btn btn-success" href="{{ route('admin.reports.export', ['month' => $month, 'company' => $company, 'type' => $type]) }}">⬇ Export Excel (CSV)</a>
    </form>

    <p class="text-muted small mb-2">{{ $vouchers->count() }} voucher(s) found.</p>

    @if($vouchers->isEmpty())
        <div class="empty-state"><strong>No vouchers for this selection</strong><span>Try another month or company.</span></div>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th>Voucher</th><th>Type</th><th>Guest</th><th>Company</th><th>Supplier</th><th>Consultant</th><th>Issued</th><th>Dates</th></tr></thead>
                <tbody>
                @foreach($vouchers as $v)
                    <tr>
                        <td><strong>{{ $v->voucher_number }}</strong></td>
                        <td><span class="file-tag">{{ $v->typeLabel() }}</span></td>
                        <td>{{ $v->guest_name ?: '—' }}</td>
                        <td>{{ $v->client_company ?: '—' }}</td>
                        <td>{{ $v->supplier ?: '—' }}</td>
                        <td>{{ $v->employee?->name ?? '—' }}</td>
                        <td>{{ $v->created_at?->format('d M Y') }}</td>
                        <td>{{ $v->check_in_date?->format('d M') }} → {{ $v->check_out_date?->format('d M Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
