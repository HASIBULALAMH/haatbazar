<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HaatBazar') — Bangladesh's Premium Marketplace</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/store.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="bg-canvas">
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>
</div>
<div class="bg-grid"></div>

{{-- NAVBAR --}}
<nav class="navbar">
    <div class="nav-container">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="nav-logo">
            <span class="logo-text">HaatBazar</span>
        </a>

        {{-- Search --}}
        <form action="{{ route('products.index') }}" method="GET" class="nav-search">
            <i class="fa fa-search nav-search-icon"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search products..." class="nav-search-input">
            <button type="submit" class="nav-search-btn">Search</button>
        </form>

        {{-- Nav Actions --}}
        <div class="nav-actions">
            @if(Auth::guard('admin')->check())
                <a href="{{ route('admin.dashboard') }}" class="nav-btn nav-btn-ghost">
                    <i class="fa fa-gauge"></i> Admin
                </a>
            @elseif(Auth::guard('seller')->check())
                <a href="{{ route('seller.dashboard') }}" class="nav-btn nav-btn-ghost">
                    <i class="fa fa-store"></i> My Shop
                </a>
            @elseif(Auth::guard('buyer')->check())
                <a href="{{ route('buyer.dashboard') }}" class="nav-btn nav-btn-ghost">
                    <i class="fa fa-user"></i> {{ explode(' ', Auth::guard('buyer')->user()->name)[0] }}
                </a>
            @else
                <a href="{{ route('buyer.login') }}" class="nav-btn nav-btn-ghost">
                    <i class="fa fa-user"></i> Login
                </a>
                <a href="{{ route('buyer.register') }}" class="nav-btn nav-btn-primary">
                    Sign Up
                </a>
            @endif

            <a href="#" class="nav-icon-btn" title="Cart">
                <i class="fa fa-cart-shopping"></i>
                <span class="nav-badge">0</span>
            </a>
        </div>
    </div>

    {{-- Category Bar --}}
    <div class="category-bar">
        <div class="nav-container">
            <a href="{{ route('products.index') }}" class="cat-link {{ !request('category') ? 'active' : '' }}">
                <i class="fa fa-grid-2"></i> All
            </a>
            @foreach(\App\Models\Category::whereNull('parent_id')->take(8)->get() as $cat)
                <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                   class="cat-link {{ request('category') === $cat->slug ? 'active' : '' }}">
                    <i class="fa {{ $cat->icon ?? 'fa-tag' }}"></i> {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</nav>

{{-- MAIN CONTENT --}}
<main class="main-wrap">
    @if(session('success'))
        <div class="flash-success">
            <i class="fa fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="footer">
    <div class="nav-container">
        <div class="footer-grid">
            <div>
                <span class="logo-text" style="font-size:22px;">HaatBazar</span>
                <p style="font-size:13px; color:var(--text-muted); margin-top:10px; line-height:1.7;">
                    Bangladesh's premium multi-vendor marketplace. Shop from thousands of verified sellers.
                </p>
            </div>
            <div>
                <h4 style="font-size:13px; font-weight:600; letter-spacing:1px; text-transform:uppercase; color:var(--text-muted); margin-bottom:14px;">Quick Links</h4>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <a href="{{ route('home') }}" class="footer-link">Home</a>
                    <a href="{{ route('products.index') }}" class="footer-link">Products</a>
                    <a href="{{ route('seller.register') }}" class="footer-link">Become a Seller</a>
                </div>
            </div>
            <div>
                <h4 style="font-size:13px; font-weight:600; letter-spacing:1px; text-transform:uppercase; color:var(--text-muted); margin-bottom:14px;">Account</h4>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    @if(Auth::guard('admin')->check())
                        <a href="{{ route('admin.dashboard') }}" class="footer-link">Dashboard</a>
                    @elseif(Auth::guard('seller')->check())
                        <a href="{{ route('seller.dashboard') }}" class="footer-link">My Store</a>
                    @elseif(Auth::guard('buyer')->check())
                        <a href="{{ route('buyer.dashboard') }}" class="footer-link">My Account</a>
                    @else
                        <a href="{{ route('buyer.login') }}" class="footer-link">Login</a>
                        <a href="{{ route('buyer.register') }}" class="footer-link">Register</a>
                    @endif
                </div>
            </div>
        </div>
        <div style="border-top:1px solid var(--border); margin-top:32px; padding-top:20px; text-align:center; color:var(--text-muted); font-size:12px;">
            © {{ date('Y') }} HaatBazar. All rights reserved.
        </div>
    </div>
</footer>

<script src="{{ asset('assets/js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
