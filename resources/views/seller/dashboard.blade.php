@extends('layouts.seller')

@section('title', 'Seller Dashboard')

@section('content')

{{-- Pending Approval Alert --}}
@if(Auth::user()->shop && !Auth::user()->shop->is_approved)
    <div style="background:rgba(245,158,11,0.08); border:1px solid rgba(245,158,11,0.2); border-radius:12px; padding:14px 18px; margin-bottom:24px; display:flex; align-items:center; gap:12px;">
        <i class="fa fa-clock" style="color:#fcd34d; font-size:18px;"></i>
        <div>
            <div style="font-weight:600; color:#fcd34d; font-size:14px;">Shop Pending Approval</div>
            <div style="font-size:13px; color:var(--text-muted);">Your shop is under review. You'll be notified once approved.</div>
        </div>
    </div>
@endif

{{-- No Shop Alert --}}
@if(!Auth::user()->shop)
<div style="margin-bottom:24px; padding:20px; background:rgba(217,119,6,0.08); border:1px solid rgba(217,119,6,0.2); border-radius:16px; display:flex; align-items:center; gap:16px;">
    <div style="font-size:32px;">🏪</div>
    <div style="flex:1;">
        <div style="font-weight:600; color:#fcd34d; margin-bottom:4px;">Setup Your Shop</div>
        <div style="font-size:13px; color:var(--text-muted);">You haven't created your shop yet. Create your shop to start selling!</div>
    </div>
    <a href="{{ route('seller.shop.create') }}" style="background:linear-gradient(135deg,#d97706,#b45309); color:#fff; padding:10px 20px; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none; white-space:nowrap;">
        Create Shop
    </a>
</div>
@endif

<div class="topbar">
    <div>
        <h1 class="topbar-title">Welcome, {{ explode(' ', Auth::user()->name)[0] }}! 🏪</h1>
        <p class="topbar-subtitle">Manage your store and track your sales.</p>
    </div>
    <div class="topbar-actions">
        <a href="#" class="btn-icon" title="Notifications">
            <i class="fa fa-bell"></i>
        </a>
        <a href="{{ route('seller.products.create') }}" class="btn-icon" title="Add Product">
            <i class="fa fa-plus"></i>
        </a>
    </div>
</div>

{{-- Stats Grid --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon amber"><i class="fa fa-box"></i></div>
        <div class="stat-value">{{ $stats['total_products'] }}</div>
        <div class="stat-label">Total Products</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa fa-bag-shopping"></i></div>
        <div class="stat-value">{{ $stats['total_orders'] }}</div>
        <div class="stat-label">Total Orders</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa fa-bangladeshi-taka-sign"></i></div>
        <div class="stat-value">৳{{ number_format($stats['total_earnings'], 0) }}</div>
        <div class="stat-label">Total Earnings</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fa fa-star"></i></div>
        <div class="stat-value">{{ $stats['avg_rating'] }}</div>
        <div class="stat-label">Avg. Rating</div>
    </div>
</div>

{{-- Quick Actions --}}
<h2 class="section-title">Quick Actions</h2>
<div class="quick-actions">
    <a href="{{ route('seller.products.create') }}" class="quick-action">
        <div class="quick-action-icon">➕</div>
        <div class="quick-action-label">Add Product</div>
    </a>
    <a href="{{ route('seller.products.index') }}" class="quick-action">
        <div class="quick-action-icon">📦</div>
        <div class="quick-action-label">My Products</div>
    </a>
    <a href="{{ route('seller.shop.edit') }}" class="quick-action">
        <div class="quick-action-icon">🏪</div>
        <div class="quick-action-label">Edit Shop</div>
    </a>
    <a href="#" class="quick-action">
        <div class="quick-action-icon">🏷️</div>
        <div class="quick-action-label">Add Coupon</div>
    </a>
    <a href="#" class="quick-action">
        <div class="quick-action-icon">⭐</div>
        <div class="quick-action-label">View Reviews</div>
    </a>
    <a href="#" class="quick-action">
        <div class="quick-action-icon">💰</div>
        <div class="quick-action-label">Earnings</div>
    </a>
</div>

{{-- Recent Products --}}
<h2 class="section-title">Recent Products</h2>
<div class="card" style="padding:0; overflow:hidden;">
    @forelse($recentProducts as $product)
    <div style="display:flex; align-items:center; gap:14px; padding:14px 16px; border-bottom:1px solid var(--border);">
        {{-- Image --}}
        @if($product->primaryImage)
            <img src="{{ asset('storage/' . $product->primaryImage->image) }}"
                style="width:44px; height:44px; border-radius:8px; object-fit:cover; border:1px solid var(--border); flex-shrink:0;">
        @else
            <div style="width:44px; height:44px; border-radius:8px; background:rgba(217,119,6,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="fa fa-image" style="color:#fcd34d;"></i>
            </div>
        @endif

        {{-- Info --}}
        <div style="flex:1; min-width:0;">
            <div style="font-size:14px; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $product->name }}</div>
            <div style="font-size:12px; color:var(--text-muted);">
                <i class="fa {{ $product->category->icon ?? 'fa-tag' }}"></i> {{ $product->category->name }}
            </div>
        </div>

        {{-- Price --}}
        <div style="text-align:right; flex-shrink:0;">
            <div style="font-weight:600; font-size:14px;">৳{{ number_format($product->price, 0) }}</div>
            <div style="font-size:12px; color: {{ $product->stock > 0 ? '#86efac' : '#fca5a5' }}">
                Stock: {{ $product->stock }}
            </div>
        </div>

        {{-- Status --}}
        <div style="flex-shrink:0;">
            @if($product->is_active)
                <span class="badge" style="background:rgba(22,163,74,0.1); color:#86efac;">Active</span>
            @else
                <span class="badge" style="background:rgba(239,68,68,0.1); color:#fca5a5;">Inactive</span>
            @endif
        </div>

        {{-- Edit --}}
        <a href="{{ route('seller.products.edit', $product) }}"
           style="font-size:12px; color:#fcd34d; padding:5px 10px; background:rgba(217,119,6,0.1); border-radius:6px; text-decoration:none; flex-shrink:0;">
            <i class="fa fa-pen"></i>
        </a>
    </div>
    @empty
    <div class="empty-state">
        <i class="fa fa-box-open"></i>
        <p>No products yet.</p>
        <a href="{{ route('seller.products.create') }}" style="color:#fcd34d; font-size:13px; margin-top:8px; display:inline-block;">
            Add Your First Product →
        </a>
    </div>
    @endforelse
</div>

@endsection
