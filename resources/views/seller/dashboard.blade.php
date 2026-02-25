@extends('layouts.seller')

@section('title', 'Seller Dashboard')

@section('content')

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
        <div class="stat-value">0</div>
        <div class="stat-label">Total Products</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa fa-bag-shopping"></i></div>
        <div class="stat-value">0</div>
        <div class="stat-label">Total Orders</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa fa-bangladeshi-taka-sign"></i></div>
        <div class="stat-value">৳0</div>
        <div class="stat-label">Total Earnings</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fa fa-star"></i></div>
        <div class="stat-value">0.0</div>
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
    <a href="#" class="quick-action">
        <div class="quick-action-icon">📦</div>
        <div class="quick-action-label">Manage Orders</div>
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

{{-- Recent Orders --}}
<h2 class="section-title">Recent Orders</h2>
<div class="card" style="padding: 20px;">
    <div class="empty-state">
        <i class="fa fa-bag-shopping"></i>
        <p>No orders yet. Start by adding your products!</p>
        <a href="{{ route('seller.products.create') }}" style="color:#fcd34d; font-size:13px; margin-top:8px; display:inline-block;">
            Add Your First Product →
        </a>
    </div>
</div>

{{-- Shop Status --}}
@if(!Auth::user()->shop)
<div style="margin-top:24px; padding:20px; background:rgba(217,119,6,0.08); border:1px solid rgba(217,119,6,0.2); border-radius:16px; display:flex; align-items:center; gap:16px;">
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

@endsection
