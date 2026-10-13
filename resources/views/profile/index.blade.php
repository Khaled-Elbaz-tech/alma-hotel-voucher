@extends('layouts.app')
@section('title', 'My Vouchers | Alma Travel')
@section('content')
<section class="result-page">
    <div class="container-xl">
        <div class="result-heading row align-items-end mb-4">
            <div class="col-lg-8">
                <p class="eyebrow">My profile</p>
                <h1>My Vouchers</h1>
                <p class="lead mb-0">All vouchers you have generated.</p>
            </div>
        </div>
        <div class="admin-card">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead><tr><th>Voucher No.</th><th>Type</th><th>Guest</th><th>Created</th><th></th></tr></thead>
                    <tbody>
                    @forelse($vouchers as $voucher)
                        <tr>
                            <td><strong>{{ $voucher->voucher_number }}</strong></td>
                            <td>{{ ucfirst($voucher->voucher_type) }}</td>
                            <td>{{ $voucher->guest_name ?? '—' }}</td>
                            <td>{{ $voucher->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                @php
                                    $routePrefix = match($voucher->voucher_type) {
                                        'hotel' => 'hotel-voucher',
                                        'car-rental' => 'car-rental-voucher',
                                        'transfers' => 'transfers-voucher',
                                        'vip-meet-assist' => 'vip-meet-assist-voucher',
                                        default => 'hotel-voucher',
                                    };
                                @endphp
                                <a class="text-link" href="{{ route($routePrefix.'.show', $voucher) }}">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-row">No vouchers yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $vouchers->links() }}</div>
        </div>
    </div>
</section>
@endsection
