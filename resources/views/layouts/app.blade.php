<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Alma Travel Voucher Generator')</title>
    <script>document.documentElement.setAttribute('data-js','active');document.title='JS_TEST_PASSED';window.__jsWorks=true;</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="site-header">
        <div class="container-xl d-flex align-items-center justify-content-between gap-3">
            <a class="brand-lockup" href="{{ route('home') }}">
                <img src="{{ asset('images/alma-logo.png') }}" alt="Alma Travel" class="brand-logo-img">
                <span><strong>ALMA TRAVEL</strong><small>Voucher Generator</small></span>
            </a>
            <nav class="voucher-nav">
                <a class="nav-item {{ request()->is('hotel-voucher') || request()->is('/') ? 'active' : '' }}" href="{{ route('hotel-voucher.create') }}">Hotel Voucher</a>
                <a class="nav-item {{ request()->is('car-rental') ? 'active' : '' }}" href="{{ route('car-rental-voucher.create') }}">Car Rental Voucher</a>
                <a class="nav-item {{ request()->is('transfers') ? 'active' : '' }}" href="{{ route('transfers-voucher.create') }}">Transfers Voucher</a>
                <a class="nav-item {{ request()->is('vip-meet-assist') ? 'active' : '' }}" href="{{ route('vip-meet-assist-voucher.create') }}">VIP Meet &amp; Assist</a>
            </nav>
            <div class="header-actions">
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
