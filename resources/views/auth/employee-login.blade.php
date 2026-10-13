<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in | Alma Travel Voucher Generator</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; min-height: 100vh; display: flex; background: #0f0d0b; color: #fff; }
        .login-brand { flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 60px; background: linear-gradient(135deg, #1a1512 0%, #0f0d0b 100%); position: relative; overflow: hidden; }
        .login-brand::before { content: ''; position: absolute; width: 600px; height: 600px; border-radius: 50%; background: radial-gradient(circle, rgba(201,162,39,.12) 0%, transparent 70%); top: -200px; right: -200px; }
        .login-brand::after { content: ''; position: absolute; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle, rgba(139,0,0,.15) 0%, transparent 70%); bottom: -150px; left: -150px; }
        .brand-logo { width: 280px; max-width: 80%; margin-bottom: 40px; position: relative; z-index: 1; }
        .brand-tagline { font-size: 28px; font-weight: 300; letter-spacing: 2px; color: #c9a227; position: relative; z-index: 1; text-align: center; }
        .brand-sub { margin-top: 16px; font-size: 14px; color: rgba(255,255,255,.5); letter-spacing: 1px; position: relative; z-index: 1; }
        .login-form-side { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px; background: #fff; color: #1a1a1a; }
        .login-box { width: 100%; max-width: 400px; }
        .login-box h1 { font-size: 32px; font-weight: 800; margin-bottom: 8px; color: #1a1a1a; }
        .login-box .subtitle { color: #666; margin-bottom: 32px; font-size: 15px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #333; text-transform: uppercase; letter-spacing: .5px; }
        .form-group input { width: 100%; padding: 14px 16px; border: 2px solid #e5e0d8; border-radius: 10px; font-size: 15px; transition: border-color .2s; }
        .form-group input:focus { outline: none; border-color: #c9a227; }
        .form-check { display: flex; align-items: center; gap: 8px; margin-bottom: 24px; font-size: 14px; color: #555; }
        .btn-login { width: 100%; padding: 15px; background: linear-gradient(135deg, #8B0000 0%, #a00000 100%); color: #fff; border: none; border-radius: 10px; font-size: 16px; font-weight: 700; cursor: pointer; transition: transform .15s, box-shadow .15s; }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(139,0,0,.3); }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .login-footer { margin-top: 32px; text-align: center; font-size: 12px; color: #999; }
        @media (max-width: 768px) { .login-brand { display: none; } }
    </style>
</head>
<body>
    <div class="login-brand">
        <img src="{{ asset('images/Alma travel_logos_final.png') }}" alt="Alma Travel" class="brand-logo">
        <div class="brand-tagline">We Always Care</div>
        <div class="brand-sub">VOUCHER GENERATOR &mdash; EMPLOYEE PORTAL</div>
    </div>
    <div class="login-form-side">
        <div class="login-box">
            <h1>Welcome back</h1>
            <p class="subtitle">Sign in to generate and manage your vouchers.</p>
            @if($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@almatravel.co">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>
                <div class="form-check">
                    <input type="checkbox" name="remember" id="remember" value="1">
                    <label for="remember" style="text-transform:none; font-weight:400;">Remember me</label>
                </div>
                <button type="submit" class="btn-login">Sign In</button>
            </form>
            <div class="login-footer">&copy; {{ date('Y') }} Alma Travel. Internal use only.</div>
        </div>
    </div>
</body>
</html>
