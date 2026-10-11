@extends('layouts.app')
@section('title', 'Sign in | Alma Travel')
@section('content')
<section class="auth-page">
    <div class="auth-card">
        <div class="auth-visual">
            <div class="auth-glow"></div>
            <span class="auth-kicker">Internal access</span>
            <h1>Admin workspace</h1>
            <p>Manage the people, policies, and brand details behind every Alma Travel confirmation.</p>
            <div class="auth-quote">Your Journey,<br><strong>OUR COMMITMENT</strong></div>
        </div>
        <div class="auth-form-wrap">
            <a class="back-link" href="{{ route('home') }}">Back to voucher generator</a>
            <p class="eyebrow">Secure sign in</p>
            <h2>Welcome back</h2>
            <p class="text-muted mb-4">Use your Alma Travel administrator credentials.</p>
            <form method="POST" action="{{ route('admin.login.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input id="email" name="email" type="email" class="form-control form-control-lg" value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" name="password" type="password" class="form-control form-control-lg" required autocomplete="current-password">
                </div>
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                </div>
                @if ($errors->has('email'))
                    <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                @endif
                <button class="btn btn-alma btn-lg w-100" type="submit">Sign in to admin</button>
            </form>
        </div>
    </div>
</section>
@endsection
