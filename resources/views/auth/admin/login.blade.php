<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HaatBazar — Admin Login</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .logo-badge.admin { background: rgba(99,102,241,0.15) !important; border-color: rgba(99,102,241,0.3) !important; color: #a5b4fc !important; }
        .btn-submit { background: linear-gradient(135deg, #6366f1, #4f46e5); }
        .btn-submit:hover { box-shadow: 0 8px 25px rgba(99,102,241,0.4); }
        .bg-orb-1 { background: #6366f1 !important; }
        .bg-orb-2 { background: #4f46e5 !important; }
        .form-input:focus { border-color: #6366f1 !important; box-shadow: 0 0 0 3px rgba(99,102,241,0.1) !important; }
        .input-wrapper:focus-within .input-icon { color: #6366f1 !important; }
    </style>
</head>
<body class="auth-page">

<div class="bg-canvas">
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>
</div>
<div class="bg-grid"></div>

<div class="auth-wrapper">
    <div class="auth-card card">

        <div class="auth-logo">
            <div class="logo-text">HaatBazar</div>
            <div class="logo-badge admin">ADMIN PANEL</div>
        </div>

        <h2 class="auth-title">Admin Access</h2>
        <p class="auth-subtitle">Restricted area — authorized personnel only</p>

        @if(session('status'))
            <div class="alert-success">
                <i class="fa fa-circle-check"></i> {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <p><i class="fa fa-circle-exclamation" style="margin-right:6px;"></i>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <i class="fa fa-envelope input-icon"></i>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="form-input @error('email') is-invalid @enderror"
                        placeholder="admin@haatbazar.com" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <i class="fa fa-lock input-icon"></i>
                    <input type="password" name="password" id="password"
                        class="form-input @error('password') is-invalid @enderror"
                        placeholder="Enter admin password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                <input type="checkbox" name="remember" id="remember"
                    style="width:16px; height:16px; accent-color:#6366f1; cursor:pointer;">
                <label for="remember" style="font-size:13px; color:var(--text-muted); cursor:pointer;">Remember me</label>
            </div>

            <button type="submit" class="btn-submit">
                <span class="btn-text">Login to Admin Panel &nbsp;<i class="fa fa-shield-halved"></i></span>
                <span class="btn-loader"><i class="fa fa-spinner fa-spin"></i> &nbsp;Authenticating...</span>
            </button>
        </form>

        <div style="text-align:center; margin-top:20px;">
            <a href="{{ route('buyer.login') }}" style="font-size:12px; color:var(--text-muted);">
                ← Back to main site
            </a>
        </div>

    </div>
</div>

<script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
