<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Alma Travel Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a class="brand-lockup sidebar-brand" href="{{ route('admin.dashboard') }}">
                <span class="brand-mark">AT</span>
                <span><strong>ALMA TRAVEL</strong><small>Admin workspace</small></span>
            </a>
            <nav class="admin-nav mt-5">
                <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><span>01</span> Dashboard</a>
                <a class="{{ request()->routeIs('admin.employees.*') ? 'active' : '' }}" href="{{ route('admin.employees.index') }}"><span>02</span> Employees</a>
                <a class="{{ request()->routeIs('admin.lookups.*') ? 'active' : '' }}" href="{{ route('admin.lookups.index') }}"><span>03</span> Lookup values</a>
                <a class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.edit') }}"><span>04</span> Company settings</a>
                <a class="{{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}" href="{{ route('admin.vouchers.index') }}"><span>05</span> Voucher logs</a>
                <a class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><span>06</span> Users</a>
            </nav>
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="btn btn-outline-light btn-sm w-100" type="submit">Sign out</button>
                </form>
                <a class="text-white-50 small" href="{{ route('home') }}">Open voucher generator</a>
            </div>
        </aside>
        <section class="admin-content">
            <div class="admin-topbar d-flex align-items-center justify-content-between gap-3">
                <div>
                    <p class="eyebrow mb-1">Alma Travel / Internal</p>
                    <h1 class="h3 mb-0">@yield('page-title', 'Admin dashboard')</h1>
                </div>
                <span class="admin-user">{{ auth()->user()->name }}</span>
            </div>
            <div class="admin-page">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <strong>Please correct the following:</strong>
                        <ul class="mb-0 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </section>
    </div>
    @yield('scripts')
</body>
</html>
