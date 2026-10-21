@extends('layouts.app')
@section('title', 'My Vouchers | Alma Travel')
@section('content')
<style>
    .mv-wrap { --mv-red:#8B251E; --mv-red-dark:#5F1712; --mv-ink:#1F1B18; --mv-mut:#8A7F76; --mv-line:#E9E2D9; --mv-soft:#FAF7F3; }
    .mv-ic { width:15px; height:15px; flex:none; }
    .mv-hero { background: linear-gradient(120deg, #8B251E 0%, #6E1B15 55%, #521510 100%); border-radius:18px; padding:30px 34px; color:#fff; margin-bottom:22px; position:relative; overflow:hidden; box-shadow:0 12px 30px rgba(139,37,30,.22); }
    .mv-hero::after { content:''; position:absolute; width:420px; height:420px; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.13) 0%, transparent 65%); top:-190px; right:-110px; pointer-events:none; }
    .mv-hero .mv-eyebrow { font-size:11px; font-weight:700; letter-spacing:3px; text-transform:uppercase; color:#F2C4BC; margin:0 0 6px; position:relative; z-index:1; }
    .mv-hero h1 { font-size:30px; font-weight:800; margin:0 0 6px; position:relative; z-index:1; letter-spacing:.2px; }
    .mv-hero .mv-sub { color:rgba(255,255,255,.75); font-size:14px; margin:0; position:relative; z-index:1; }
    .mv-hero .mv-email { display:inline-flex; align-items:center; gap:8px; margin-top:16px; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.18); padding:7px 14px; border-radius:999px; font-size:13px; position:relative; z-index:1; }
    .mv-stats { display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:14px; margin-bottom:22px; }
    .mv-stat { background:#fff; border:1px solid var(--mv-line); border-radius:14px; padding:16px 18px; display:flex; align-items:center; gap:14px; transition:transform .15s, box-shadow .15s; }
    .mv-stat:hover { transform:translateY(-2px); box-shadow:0 10px 22px rgba(31,27,24,.07); }
    .mv-stat .mv-chip { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex:none; }
    .mv-stat .mv-chip svg { width:21px; height:21px; }
    .mv-stat .mv-num { font-size:24px; font-weight:800; color:var(--mv-ink); line-height:1.05; }
    .mv-stat .mv-lbl { font-size:11px; font-weight:600; color:var(--mv-mut); text-transform:uppercase; letter-spacing:1.2px; }
    .mv-chip-total { background:#F7E9E7; color:#8B251E; }
    .mv-chip-hotel { background:#E8F5E9; color:#2E7D32; }
    .mv-chip-car { background:#E3F0FB; color:#1565C0; }
    .mv-chip-tr { background:#FFF3E0; color:#E65100; }
    .mv-chip-vip { background:#F3E5F5; color:#7B1FA2; }
    .mv-toolbar { background:#fff; border:1px solid var(--mv-line); border-radius:14px; padding:16px 18px; margin-bottom:20px; display:flex; align-items:flex-end; gap:14px; flex-wrap:wrap; }
    .mv-toolbar .mv-field { display:flex; flex-direction:column; gap:5px; }
    .mv-toolbar label { font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--mv-mut); }
    .mv-toolbar .form-select, .mv-toolbar .form-control { border-color:var(--mv-line); border-radius:9px; font-size:13.5px; min-height:40px; }
    .mv-toolbar .form-select:focus, .mv-toolbar .form-control:focus { border-color:#8B251E; box-shadow:0 0 0 3px rgba(139,37,30,.12); }
    .mv-btn-filter { background:var(--mv-red); border:1px solid var(--mv-red); color:#fff; border-radius:9px; padding:9px 22px; font-weight:700; font-size:13.5px; min-height:40px; }
    .mv-btn-filter:hover { background:var(--mv-red-dark); color:#fff; }
    .mv-clear { font-size:13px; font-weight:600; color:var(--mv-red); text-decoration:none; padding-bottom:10px; }
    .mv-count { margin-left:auto; font-size:13px; color:var(--mv-mut); padding-bottom:10px; }
    .mv-count strong { color:var(--mv-ink); }
    .mv-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(330px, 1fr)); gap:16px; }
    .mv-card { background:#fff; border:1px solid var(--mv-line); border-radius:14px; padding:18px; display:flex; flex-direction:column; gap:11px; transition:transform .15s, box-shadow .15s; }
    .mv-card:hover { transform:translateY(-2px); box-shadow:0 12px 26px rgba(31,27,24,.08); border-color:#DCD2C6; }
    .mv-card-top { display:flex; align-items:center; justify-content:space-between; gap:10px; }
    .mv-num { font-size:15px; font-weight:800; color:var(--mv-ink); letter-spacing:.2px; }
    .mv-badge { display:inline-block; padding:4px 11px; border-radius:999px; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.6px; white-space:nowrap; }
    .mv-badge.hotel { background:#E8F5E9; color:#2E7D32; }
    .mv-badge.car-rental { background:#E3F0FB; color:#1565C0; }
    .mv-badge.transfers { background:#FFF3E0; color:#E65100; }
    .mv-badge.vip-meet-assist { background:#F3E5F5; color:#7B1FA2; }
    .mv-guest { display:flex; align-items:center; gap:8px; font-size:14px; font-weight:600; color:#3D362F; }
    .mv-guest .mv-ic { color:#B4A797; }
    .mv-guest .mv-issued { margin-left:auto; font-size:12px; font-weight:500; color:#B0A696; white-space:nowrap; }
    .mv-meta { background:var(--mv-soft); border:1px solid #F0E9E1; border-radius:10px; padding:9px 12px; display:flex; align-items:center; gap:16px; flex-wrap:wrap; font-size:12.5px; color:#5C554D; }
    .mv-meta .mv-mi { display:inline-flex; align-items:center; gap:6px; }
    .mv-meta .mv-ic { color:#B4A797; width:14px; height:14px; }
    .mv-pay { padding:2px 9px; border-radius:999px; font-size:11px; font-weight:700; }
    .mv-pay.credit { background:#F7E9E7; color:#8B251E; }
    .mv-pay.cash { background:#ECECEC; color:#5C554D; }
    .mv-actions { display:flex; gap:7px; flex-wrap:wrap; margin-top:auto; padding-top:3px; }
    .mv-act { display:inline-flex; align-items:center; gap:6px; font-size:12.5px; font-weight:700; padding:8px 12px; border-radius:9px; text-decoration:none; border:1px solid transparent; transition:filter .12s, background .12s; cursor:pointer; }
    .mv-act:hover { filter:brightness(.97); text-decoration:none; }
    .mv-act-view { background:var(--mv-red); color:#fff !important; }
    .mv-act-view:hover { background:var(--mv-red-dark); }
    .mv-act-ghost { background:#fff; border-color:var(--mv-line); color:#4A433C !important; }
    .mv-act-ghost:hover { background:var(--mv-soft); }
    .mv-act-wa { background:#E7F7EE; color:#128C4A !important; }
    .mv-act-wa:hover { background:#D9F2E3; }
    .mv-empty { background:#fff; border:1px dashed #D8CFC2; border-radius:14px; text-align:center; padding:56px 20px; color:var(--mv-mut); }
    .mv-empty .mv-ic { width:40px; height:40px; color:#CBBFAE; margin-bottom:10px; }
    .mv-empty h3 { color:var(--mv-ink); font-size:18px; font-weight:800; margin-bottom:6px; }
</style>

<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <defs>
        <symbol id="mv-i-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></symbol>
        <symbol id="mv-i-copy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></symbol>
        <symbol id="mv-i-link" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></symbol>
        <symbol id="mv-i-chat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></symbol>
        <symbol id="mv-i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></symbol>
        <symbol id="mv-i-cal" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></symbol>
        <symbol id="mv-i-case" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></symbol>
        <symbol id="mv-i-mail" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></symbol>
        <symbol id="mv-i-layers" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></symbol>
        <symbol id="mv-i-home" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></symbol>
        <symbol id="mv-i-car" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5.5 11L7 6.6A2 2 0 0 1 8.9 5.2h6.2A2 2 0 0 1 17 6.6L18.5 11"/><path d="M3 17v-4a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v4"/><path d="M3 17h18"/><circle cx="7.5" cy="17.5" r="1.7"/><circle cx="16.5" cy="17.5" r="1.7"/></symbol>
        <symbol id="mv-i-repeat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></symbol>
        <symbol id="mv-i-star" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></symbol>
        <symbol id="mv-i-ticket" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9V7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a3 3 0 0 0 0 6v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a3 3 0 0 0 0-6z"/><line x1="13" y1="5" x2="13" y2="7"/><line x1="13" y1="11" x2="13" y2="13"/><line x1="13" y1="17" x2="13" y2="19"/></symbol>
    </defs>
</svg>

<section class="result-page mv-wrap">
    <div class="container-xl">
        <div class="mv-hero">
            <p class="mv-eyebrow">My Profile</p>
            <h1>My Vouchers</h1>
            <p class="mv-sub">Every voucher you have generated — view, copy, or share it in one click.</p>
            <div class="mv-email"><svg class="mv-ic"><use href="#mv-i-mail"/></svg>{{ auth()->user()->email }}</div>
        </div>

        @php
            $totalCount = $vouchers->total();
            $typeCounts = \App\Models\Voucher::where('user_id', auth()->id())
                ->selectRaw('voucher_type, count(*) as c')->groupBy('voucher_type')->pluck('c', 'voucher_type');
        @endphp

        <div class="mv-stats">
            <div class="mv-stat"><div class="mv-chip mv-chip-total"><svg><use href="#mv-i-layers"/></svg></div><div><div class="mv-num">{{ $totalCount }}</div><div class="mv-lbl">Total Vouchers</div></div></div>
            <div class="mv-stat"><div class="mv-chip mv-chip-hotel"><svg><use href="#mv-i-home"/></svg></div><div><div class="mv-num">{{ $typeCounts['hotel'] ?? 0 }}</div><div class="mv-lbl">Hotel</div></div></div>
            <div class="mv-stat"><div class="mv-chip mv-chip-car"><svg><use href="#mv-i-car"/></svg></div><div><div class="mv-num">{{ $typeCounts['car-rental'] ?? 0 }}</div><div class="mv-lbl">Car Rental</div></div></div>
            <div class="mv-stat"><div class="mv-chip mv-chip-tr"><svg><use href="#mv-i-repeat"/></svg></div><div><div class="mv-num">{{ $typeCounts['transfers'] ?? 0 }}</div><div class="mv-lbl">Transfers</div></div></div>
            <div class="mv-stat"><div class="mv-chip mv-chip-vip"><svg><use href="#mv-i-star"/></svg></div><div><div class="mv-num">{{ $typeCounts['vip-meet-assist'] ?? 0 }}</div><div class="mv-lbl">VIP</div></div></div>
        </div>

        <form method="GET" action="{{ route('profile') }}" class="mv-toolbar">
            <div class="mv-field">
                <label for="mv-f-type">Type</label>
                <select id="mv-f-type" name="type" class="form-select" style="min-width:170px;" onchange="this.form.submit()">
                    <option value="">All types</option>
                    <option value="hotel" {{ request('type')==='hotel'?'selected':'' }}>Hotel</option>
                    <option value="car-rental" {{ request('type')==='car-rental'?'selected':'' }}>Car Rental</option>
                    <option value="transfers" {{ request('type')==='transfers'?'selected':'' }}>Transfers</option>
                    <option value="vip-meet-assist" {{ request('type')==='vip-meet-assist'?'selected':'' }}>VIP Meet &amp; Assist</option>
                </select>
            </div>
            <div class="mv-field">
                <label for="mv-f-ct">Payment</label>
                <select id="mv-f-ct" name="client_type" class="form-select" style="min-width:140px;" onchange="this.form.submit()">
                    <option value="">Credit / Cash</option>
                    <option value="credit" {{ request('client_type')==='credit'?'selected':'' }}>Credit</option>
                    <option value="cash" {{ request('client_type')==='cash'?'selected':'' }}>Cash</option>
                </select>
            </div>
            <div class="mv-field">
                <label for="mv-f-co">Company</label>
                <input id="mv-f-co" name="company" class="form-control" style="min-width:190px;" value="{{ request('company') }}" placeholder="Filter by company…">
            </div>
            <button class="mv-btn-filter" type="submit">Filter</button>
            @if(request('type')||request('client_type')||request('company'))
                <a href="{{ route('profile') }}" class="mv-clear">✕ Clear filters</a>
            @endif
            <span class="mv-count">Showing <strong>{{ $vouchers->count() }}</strong> of <strong>{{ $vouchers->total() }}</strong> vouchers</span>
        </form>

        @if($vouchers->count())
        <div class="mv-grid">
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
            <div class="mv-card">
                <div class="mv-card-top">
                    <span class="mv-num">{{ $voucher->voucher_number }}</span>
                    <span class="mv-badge {{ $voucher->voucher_type }}">{{ $typeLabel }}</span>
                </div>
                <div class="mv-guest">
                    <svg class="mv-ic"><use href="#mv-i-user"/></svg>
                    <span>{{ $voucher->guest_name ?: 'No guest name' }}</span>
                    <span class="mv-issued">{{ $voucher->created_at->format('d M Y') }}</span>
                </div>
                @if($voucher->check_in_date || $voucher->client_company || $voucher->client_type)
                <div class="mv-meta">
                    @if($voucher->check_in_date)
                        <span class="mv-mi"><svg class="mv-ic"><use href="#mv-i-cal"/></svg>{{ $voucher->check_in_date->format('d M Y') }}@if($voucher->check_out_date) → {{ $voucher->check_out_date->format('d M Y') }}@endif</span>
                    @endif
                    @if($voucher->client_company)
                        <span class="mv-mi"><svg class="mv-ic"><use href="#mv-i-case"/></svg>{{ $voucher->client_company }}</span>
                    @endif
                    @if($voucher->client_type)
                        <span class="mv-pay {{ $voucher->client_type }}">{{ ucfirst($voucher->client_type) }}</span>
                    @endif
                </div>
                @endif
                <div class="mv-actions">
                    <a class="mv-act mv-act-view" href="{{ route($routePrefix.'.show', $voucher) }}"><svg class="mv-ic"><use href="#mv-i-eye"/></svg>View</a>
                    <a class="mv-act mv-act-ghost" href="{{ route($routePrefix.'.copy', $voucher) }}"><svg class="mv-ic"><use href="#mv-i-copy"/></svg>Copy as new</a>
                    <a class="mv-act mv-act-wa" target="_blank" rel="noopener" href="https://wa.me/?text={{ urlencode('Alma Travel voucher '.$voucher->voucher_number.': '.$voucher->shareUrl()) }}"><svg class="mv-ic"><use href="#mv-i-chat"/></svg>WhatsApp</a>
                    <a class="mv-act mv-act-ghost" href="#" onclick="event.preventDefault(); if (navigator.clipboard) { navigator.clipboard.writeText('{{ $voucher->shareUrl() }}'); } this.innerHTML='✓ Copied';"><svg class="mv-ic"><use href="#mv-i-link"/></svg>Copy link</a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $vouchers->links() }}</div>
        @else
        <div class="mv-empty">
            <svg class="mv-ic"><use href="#mv-i-ticket"/></svg>
            <h3>No vouchers found</h3>
            <p>
                @if(request('type')||request('client_type')||request('company'))
                    Nothing matches the current filters. <a href="{{ route('profile') }}" style="color:#8B251E;font-weight:700;">Clear the filters</a> to see all your vouchers.
                @else
                    Generate your first voucher to see it here.
                @endif
            </p>
        </div>
        @endif
    </div>
</section>
@endsection
