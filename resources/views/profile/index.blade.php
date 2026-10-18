@extends('layouts.app')
@section('title', 'My Vouchers | Alma Travel')
@section('content')
<style>
    .profile-hero { background: linear-gradient(135deg, #1a1512 0%, #2d1f1a 100%); border-radius: 16px; padding: 36px; color: #fff; margin-bottom: 28px; position: relative; overflow: hidden; }
    .profile-hero::before { content: ''; position: absolute; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle, rgba(201,162,39,.15) 0%, transparent 70%); top: -150px; right: -100px; }
    .profile-hero h1 { font-size: 28px; font-weight: 800; margin-bottom: 6px; position: relative; }
    .profile-hero p { color: rgba(255,255,255,.6); font-size: 14px; position: relative; }
    .profile-hero .user-email { display: inline-flex; align-items: center; gap: 8px; margin-top: 16px; background: rgba(255,255,255,.08); padding: 8px 16px; border-radius: 20px; font-size: 13px; position: relative; }
    .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin-bottom: 28px; }
    .stat-card { background: #fff; border: 1px solid #e8e2d8; border-radius: 12px; padding: 20px; text-align: center; }
    .stat-card .stat-num { font-size: 32px; font-weight: 800; color: #8B0000; }
    .stat-card .stat-label { font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; }
    .voucher-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; }
    .voucher-card { background: #fff; border: 1px solid #e8e2d8; border-radius: 12px; padding: 20px; transition: transform .15s, box-shadow .15s; }
    .voucher-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
    .voucher-card .v-num { font-size: 16px; font-weight: 700; color: #1a1a1a; margin-bottom: 4px; }
    .voucher-card .v-guest { font-size: 13px; color: #666; margin-bottom: 12px; }
    .voucher-card .v-meta { display: flex; justify-content: space-between; align-items: center; }
    .v-type { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
    .v-type.hotel { background: #e8f5e9; color: #2e7d32; }
    .v-type.car-rental { background: #e3f2fd; color: #1565c0; }
    .v-type.transfers { background: #fff3e0; color: #e65100; }
    .v-type.vip-meet-assist { background: #f3e5f5; color: #7b1fa2; }
    .v-date { font-size: 12px; color: #999; }
    .v-link { display: inline-block; margin-top: 12px; font-size: 13px; font-weight: 600; color: #8B0000; text-decoration: none; }
    .v-link:hover { text-decoration: underline; }
    .empty-state { text-align: center; padding: 60px 20px; color: #999; }
    .empty-state .empty-icon { font-size: 48px; margin-bottom: 16px; }
</style>

<section class="result-page">
    <div class="container-xl">
        <div class="profile-hero">
            <p class="eyebrow" style="color: #c9a227;">My Profile</p>
            <h1>My Vouchers</h1>
            <p>All vouchers you have generated, in one place.</p>
            <div class="user-email">✉️ {{ auth()->user()->email }}</div>
        </div>

        @php
            $totalCount = $vouchers->total();
            $typeCounts = \App\Models\Voucher::where('user_id', auth()->id())
                ->selectRaw('voucher_type, count(*) as c')->groupBy('voucher_type')->pluck('c', 'voucher_type');
        @endphp

        <div class="stats-row">
            <div class="stat-card"><div class="stat-num">{{ $totalCount }}</div><div class="stat-label">Total Vouchers</div></div>
            <div class="stat-card"><div class="stat-num">{{ $typeCounts['hotel'] ?? 0 }}</div><div class="stat-label">Hotel</div></div>
            <div class="stat-card"><div class="stat-num">{{ $typeCounts['car-rental'] ?? 0 }}</div><div class="stat-label">Car Rental</div></div>
            <div class="stat-card"><div class="stat-num">{{ $typeCounts['transfers'] ?? 0 }}</div><div class="stat-label">Transfers</div></div>
            <div class="stat-card"><div class="stat-num">{{ $typeCounts['vip-meet-assist'] ?? 0 }}</div><div class="stat-label">VIP</div></div>
        </div>

        <form method="GET" action="{{ route('profile') }}" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;align-items:center;">
            <select name="type" class="form-select" style="max-width:180px;" onchange="this.form.submit()">
                <option value="">All types</option>
                <option value="hotel" {{ request('type')==='hotel'?'selected':'' }}>Hotel</option>
                <option value="car" {{ request('type')==='car'?'selected':'' }}>Car Rental</option>
                <option value="transfers" {{ request('type')==='transfers'?'selected':'' }}>Transfers</option>
                <option value="vip" {{ request('type')==='vip'?'selected':'' }}>VIP</option>
            </select>
            <select name="client_type" class="form-select" style="max-width:150px;" onchange="this.form.submit()">
                <option value="">Credit/Cash</option>
                <option value="credit" {{ request('client_type')==='credit'?'selected':'' }}>Credit</option>
                <option value="cash" {{ request('client_type')==='cash'?'selected':'' }}>Cash</option>
            </select>
            <input name="company" class="form-control" style="max-width:200px;" value="{{ request('company') }}" placeholder="Filter by company…">
            <button class="btn btn-outline-dark btn-sm" type="submit">Filter</button>
            @if(request('type')||request('client_type')||request('company'))
                <a href="{{ route('profile') }}" class="btn btn-outline-dark btn-sm">Clear</a>
            @endif
        </form>
        @if($vouchers->count())
        <div class="voucher-grid">
            @foreach($vouchers as $voucher)
            @php
                $routePrefix = match($voucher->voucher_type) {
                    'hotel' => 'hotel-voucher',
                    'car-rental' => 'car-rental-voucher',
                    'transfers' => 'transfers-voucher',
                    'vip-meet-assist' => 'vip-meet-assist-voucher',
                    default => 'hotel-voucher',
                };
                $typeLabel = match($voucher->voucher_type) {
                    'hotel' => 'Hotel',
                    'car-rental' => 'Car Rental',
                    'transfers' => 'Transfers',
                    'vip-meet-assist' => 'VIP Meet & Assist',
                    default => ucfirst($voucher->voucher_type),
                };
            @endphp
            <div class="voucher-card">
                <div class="v-num">{{ $voucher->voucher_number }}</div>
                <div class="v-guest">{{ $voucher->guest_name ?? 'No guest name' }}</div>
                <div class="v-meta">
                    <span class="v-type {{ $voucher->voucher_type }}">{{ $typeLabel }}</span>
                    <span class="v-date">{{ $voucher->created_at->format('d M Y') }}</span>
                </div>
                <div style="display:flex;gap:12px;margin-top:8px;">
                    <a class="v-link" href="{{ route($routePrefix.'.show', $voucher) }}">View voucher →</a>
                    <a class="v-link" href="{{ route($routePrefix.'.copy', $voucher) }}" style="color:#8B251E;">⧉ Copy as new</a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $vouchers->links() }}</div>
        @else
        <div class="empty-state">
            <div class="empty-icon">🎫</div>
            <h3>No vouchers yet</h3>
            <p>Generate your first voucher to see it here.</p>
        </div>
        @endif
    </div>
</section>
@endsection
