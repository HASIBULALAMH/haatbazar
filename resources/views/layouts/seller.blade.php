<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HaatBazar — @yield('title', 'Seller Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')
</head>
<body>

<div class="bg-canvas">
    <div class="bg-orb bg-orb-1" style="background:#d97706;"></div>
    <div class="bg-orb bg-orb-2" style="background:#16a34a;"></div>
    <div class="bg-orb bg-orb-3"></div>
</div>
<div class="bg-grid"></div>

<div class="dashboard-layout">

    {{-- SIDEBAR --}}
    <aside class="sidebar seller-sidebar" id="sidebar">

        <div class="sidebar-logo">
            <div class="logo-text seller-logo">HaatBazar</div>
            <div class="logo-badge seller-badge">SELLER PORTAL</div>
        </div>

        <nav class="sidebar-nav">

            <div class="nav-section-title">Main Menu</div>

            <a href="{{ route('seller.dashboard') }}"
               class="nav-item {{ request()->routeIs('seller.dashboard') ? 'seller-active' : '' }}">
                <i class="fa fa-gauge nav-icon"></i> Dashboard
            </a>

            <a href="{{ route('seller.products.index') }}"
               class="nav-item {{ request()->routeIs('seller.products*') ? 'seller-active' : '' }}">
                <i class="fa fa-box nav-icon"></i> My Products
            </a>

            <a href="#"
               class="nav-item {{ request()->routeIs('seller.orders*') ? 'seller-active' : '' }}">
                <i class="fa fa-bag-shopping nav-icon"></i> Orders
            </a>

            <a href="#"
               class="nav-item {{ request()->routeIs('seller.earnings*') ? 'seller-active' : '' }}">
                <i class="fa fa-bangladeshi-taka-sign nav-icon"></i> Earnings
            </a>

            <div class="nav-section-title">Store</div>

            <a href="{{ route('seller.shop.edit') }}"
               class="nav-item {{ request()->routeIs('seller.shop*') ? 'seller-active' : '' }}">
                <i class="fa fa-store nav-icon"></i> My Shop
            </a>

            <a href="#"
               class="nav-item {{ request()->routeIs('seller.reviews*') ? 'seller-active' : '' }}">
                <i class="fa fa-star nav-icon"></i> Reviews
            </a>

            <a href="#"
               class="nav-item {{ request()->routeIs('seller.coupons*') ? 'seller-active' : '' }}">
                <i class="fa fa-tag nav-icon"></i> Coupons
            </a>

            <div class="nav-section-title">Account</div>

            <a href="#"
               class="nav-item {{ request()->routeIs('seller.profile*') ? 'seller-active' : '' }}">
                <i class="fa fa-user nav-icon"></i> My Profile
            </a>

        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar-sm seller-avatar">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="avatar">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    @endif
                </div>
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role" style="color:#fcd34d;">Seller</div>
                </div>
            </div>

            <form method="POST" action="{{ route('seller.logout') }}" class="logout-form">
                @csrf
                <button type="submit">
                    <i class="fa fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>

    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main-content">

        <div class="mobile-topbar">
            <button class="btn-icon" id="sidebar-toggle">
                <i class="fa fa-bars"></i>
            </button>
            <div class="logo-text seller-logo" style="font-size:20px;">HaatBazar</div>
            <a href="#" class="btn-icon">
                <i class="fa fa-user"></i>
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success" style="margin-bottom:20px;">
                <i class="fa fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error" style="margin-bottom:20px;">
                <p><i class="fa fa-circle-exclamation"></i> {{ session('error') }}</p>
            </div>
        @endif

        @yield('content')

    </main>

</div>

<script src="{{ asset('assets/js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
