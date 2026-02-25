<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HaatBazar — @yield('title', 'Admin Panel')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')
</head>
<body>

<div class="bg-canvas">
    <div class="bg-orb bg-orb-1" style="background:#6366f1;"></div>
    <div class="bg-orb bg-orb-2" style="background:#4f46e5;"></div>
    <div class="bg-orb bg-orb-3"></div>
</div>
<div class="bg-grid"></div>

<div class="dashboard-layout">

    {{-- SIDEBAR --}}
    <aside class="sidebar admin-sidebar" id="sidebar">

        <div class="sidebar-logo">
            <div class="logo-text admin-logo">HaatBazar</div>
            <div class="logo-badge admin-badge">ADMIN PANEL</div>
        </div>

        <nav class="sidebar-nav">

            <div class="nav-section-title">Main Menu</div>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'admin-active' : '' }}">
                <i class="fa fa-gauge nav-icon"></i> Dashboard
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="nav-item {{ request()->routeIs('admin.users*') ? 'admin-active' : '' }}">
                <i class="fa fa-users nav-icon"></i> Users
            </a>

            <a href="{{ route('admin.sellers.index') }}"
               class="nav-item {{ request()->routeIs('admin.sellers*') ? 'admin-active' : '' }}">
                <i class="fa fa-store nav-icon"></i> Sellers
            </a>

            <a href="{{ route('admin.shops.index') }}"
               class="nav-item {{ request()->routeIs('admin.shops*') ? 'admin-active' : '' }}">
                <i class="fa fa-shop nav-icon"></i> Shops
            </a>

            <div class="nav-section-title">Catalog</div>

            <a href="{{ route('admin.categories.index') }}"
               class="nav-item {{ request()->routeIs('admin.categories*') ? 'admin-active' : '' }}">
                <i class="fa fa-layer-group nav-icon"></i> Categories
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="nav-item {{ request()->routeIs('admin.products*') ? 'admin-active' : '' }}">
                <i class="fa fa-box nav-icon"></i> Products
            </a>

            <div class="nav-section-title">Orders & Finance</div>

            <a href="#"
               class="nav-item {{ request()->routeIs('admin.orders*') ? 'admin-active' : '' }}">
                <i class="fa fa-bag-shopping nav-icon"></i> Orders
            </a>

            <a href="#"
               class="nav-item {{ request()->routeIs('admin.payments*') ? 'admin-active' : '' }}">
                <i class="fa fa-bangladeshi-taka-sign nav-icon"></i> Payments
            </a>

            <a href="#"
               class="nav-item {{ request()->routeIs('admin.reports*') ? 'admin-active' : '' }}">
                <i class="fa fa-chart-line nav-icon"></i> Reports
            </a>

        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar-sm admin-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role" style="color:#a5b4fc;">Administrator</div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
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
            <div class="logo-text admin-logo" style="font-size:20px;">HaatBazar</div>
            <div class="btn-icon">
                <i class="fa fa-shield-halved"></i>
            </div>
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
