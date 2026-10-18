<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Alma Travel Voucher Generator')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script>document.documentElement.setAttribute('data-js','active');document.title='JS_TEST_PASSED';window.__jsWorks=true;</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="site-header">
        <div class="container-xl d-flex align-items-center justify-content-between gap-3">
            <a class="brand-lockup" href="{{ route('home') }}">
                <img src="{{ asset('images/Alma travel_logos_final.png') }}" alt="Alma Travel" class="brand-logo-img">
                <span><strong>ALMA TRAVEL</strong><small>Voucher Generator</small></span>
            </a>
            <nav class="voucher-nav">
                <a class="nav-item {{ request()->is('hotel-voucher') || request()->is('/') ? 'active' : '' }}" href="{{ route('hotel-voucher.create') }}">Hotel Voucher</a>
                <a class="nav-item {{ request()->is('car-rental') ? 'active' : '' }}" href="{{ route('car-rental-voucher.create') }}">Car Rental Voucher</a>
                <a class="nav-item {{ request()->is('transfers') ? 'active' : '' }}" href="{{ route('transfers-voucher.create') }}">Transfers Voucher</a>
                <a class="nav-item {{ request()->is('vip-meet-assist') ? 'active' : '' }}" href="{{ route('vip-meet-assist-voucher.create') }}">VIP Meet &amp; Assist</a>
            </nav>
            <div class="header-actions">
                @auth
                @if(auth()->user()?->is_admin)
                <a class="header-btn {{ request()->is('admin*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <span class="header-btn-icon">⚙️</span> Admin Settings
                </a>
                @endif
                <a class="header-btn {{ request()->is('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                    <span class="header-btn-icon">🎫</span> My Vouchers
                </a>
                <div class="user-chip">
                    <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="user-info">
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="user-email">{{ auth()->user()->email }}</span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf<button type="submit" class="logout-btn" title="Logout">⏻</button></form>
                </div>
                @endauth
            </div>
        </div>
    </header>
    <main>@yield('content')</main>
    <footer class="site-footer">
        <div class="container-xl d-flex flex-wrap justify-content-between gap-2">
            <span>&copy; {{ date('Y') }} Alma Travel. Internal use only.</span>
            <span>Badriya Towers, Prince Saud Al Faisal District, Jeddah</span>
        </div>
    </footer>
    @yield('scripts')
</body>
</html>
