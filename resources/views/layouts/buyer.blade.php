<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HaatBazar — @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')
</head>

<body>

    {{-- Animated Background --}}
    <div class="bg-canvas">
        <div class="bg-orb bg-orb-1"></div>
        <div class="bg-orb bg-orb-2"></div>
        <div class="bg-orb bg-orb-3"></div>
    </div>
    <div class="bg-grid"></div>

    {{-- Dashboard Layout --}}
    <div class="dashboard-layout">

        {{-- ===== SIDEBAR ===== --}}
        <aside class="sidebar" id="sidebar">

            {{-- Logo --}}
            <div class="sidebar-logo">
                <div class="logo-text">HaatBazar</div>
                <div class="logo-badge">Buyer Portal</div>
            </div>

            {{-- Navigation --}}
            <nav class="sidebar-nav">

                <div class="nav-section-title">Main Menu</div>

                <a href="{{ route('buyer.dashboard') }}"
                    class="nav-item {{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}">
                    <i class="fa fa-house nav-icon"></i>
                    Dashboard
                </a>

                <a href="{{ route('buyer.orders.index') }}" class="nav-item {{ request()->routeIs('buyer.orders*') ? 'active' : '' }}">
                    <i class="fa fa-box nav-icon"></i>
                    My Orders
                    {{-- <span class="nav-badge">3</span> --}}
                </a>

                <a href="#" class="nav-item {{ request()->routeIs('buyer.wishlist*') ? 'active' : '' }}">
                    <i class="fa fa-heart nav-icon"></i>
                    Wishlist
                </a>

                <a href="{{ route('buyer.cart.index') }}"
                    class="nav-item {{ request()->routeIs('buyer.cart*') ? 'active' : '' }}">
                    <i class="fa fa-cart-shopping nav-icon"></i>
                    @php
                        $buyerCartCount = Auth::guard('buyer')->user()->cartItems()->sum('quantity');
                    @endphp
                    @if ($buyerCartCount > 0)
                        <span class="nav-badge" id="cart-badge">{{ $buyerCartCount }}</span>
                    @else
                        <span class="nav-badge" id="cart-badge">0</span>
                    @endif
                    Cart
                </a>

                <div class="nav-section-title">Account</div>

                <a href="{{ route('buyer.profile.edit') }}"
                    class="nav-item {{ request()->routeIs('buyer.profile*') ? 'active' : '' }}">
                    <i class="fa fa-user nav-icon"></i>
                    My Profile
                </a>

                <a href="{{ route('buyer.addresses.index') }}"
                    class="nav-item {{ request()->routeIs('buyer.addresses*') ? 'active' : '' }}">
                    <i class="fa fa-location-dot nav-icon"></i>
                    Addresses
                </a>

                <a href="#" class="nav-item {{ request()->routeIs('buyer.reviews*') ? 'active' : '' }}">
                    <i class="fa fa-star nav-icon"></i>
                    My Reviews
                </a>

            </nav>

            {{-- Footer --}}
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar-sm">
                        @if (Auth::guard('buyer')->user()->avatar)
                            <img src="{{ asset('storage/' . Auth::guard('buyer')->user()->avatar) }}" alt="avatar">
                        @else
                            {{ strtoupper(substr(Auth::guard('buyer')->user()->name, 0, 2)) }}
                        @endif
                    </div>
                    <div class="user-details">
                        <div class="user-name">{{ Auth::guard('buyer')->user()->name }}</div>
                        <div class="user-role">Buyer</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('buyer.logout') }}" class="logout-form">
                    @csrf
                    <button type="submit">
                        <i class="fa fa-right-from-bracket"></i>
                        Logout
                    </button>
                </form>
            </div>

        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <main class="main-content">

            {{-- Mobile Top Bar --}}
            <div class="mobile-topbar">
                <button class="btn-icon" id="sidebar-toggle">
                    <i class="fa fa-bars"></i>
                </button>
                <div class="logo-text" style="font-size:20px;">HaatBazar</div>
                <a href="{{ route('buyer.profile.edit') }}" class="btn-icon">
                    <i class="fa fa-user"></i>
                </a>
            </div>

            {{-- Success / Error Flash --}}
            @if (session('success'))
                <div class="alert-success" style="margin-bottom:20px;">
                    <i class="fa fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert-error" style="margin-bottom:20px;">
                    <p><i class="fa fa-circle-exclamation"></i> {{ session('error') }}</p>
                </div>
            @endif

            {{-- Page Content --}}
            @yield('content')

        </main>

    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
