@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')

<div class="topbar">
    <div>
        <h1 class="topbar-title">Admin Dashboard 🛡️</h1>
        <p class="topbar-subtitle">Manage your entire marketplace from here.</p>
    </div>
    <div class="topbar-actions">
        <a href="#" class="btn-icon" title="Notifications">
            <i class="fa fa-bell"></i>
        </a>
    </div>
</div>

{{-- Stats Grid --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa fa-users"></i></div>
        <div class="stat-value">{{ $stats['total_users'] }}</div>
        <div class="stat-label">Total Buyers</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fa fa-store"></i></div>
        <div class="stat-value">{{ $stats['total_sellers'] }}</div>
        <div class="stat-label">Total Sellers</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa fa-shop"></i></div>
        <div class="stat-value">{{ $stats['total_shops'] }}</div>
        <div class="stat-label">Total Shops</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa fa-box"></i></div>
        <div class="stat-value">{{ $stats['total_products'] }}</div>
        <div class="stat-label">Total Products</div>
    </div>
</div>

{{-- Secondary Stats --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:28px;">
    <div style="background:rgba(245,158,11,0.08); border:1px solid rgba(245,158,11,0.2); border-radius:14px; padding:16px 20px; display:flex; align-items:center; justify-content:space-between;">
        <div>
            <div style="font-size:13px; color:var(--text-muted); margin-bottom:4px;">Pending Shop Approvals</div>
            <div style="font-family:'Playfair Display',serif; font-size:28px; font-weight:700; color:#fcd34d;">{{ $stats['pending_shops'] }}</div>
        </div>
        <a href="{{ route('admin.shops.index') }}"
           style="font-size:12px; color:#fcd34d; background:rgba(245,158,11,0.1); padding:8px 14px; border-radius:8px; text-decoration:none;">
            Review →
        </a>
    </div>
    <div style="background:rgba(22,163,74,0.08); border:1px solid rgba(22,163,74,0.2); border-radius:14px; padding:16px 20px; display:flex; align-items:center; justify-content:space-between;">
        <div>
            <div style="font-size:13px; color:var(--text-muted); margin-bottom:4px;">Active Products</div>
            <div style="font-family:'Playfair Display',serif; font-size:28px; font-weight:700; color:#86efac;">{{ $stats['active_products'] }}</div>
        </div>
        <a href="{{ route('admin.products.index') }}"
           style="font-size:12px; color:#86efac; background:rgba(22,163,74,0.1); padding:8px 14px; border-radius:8px; text-decoration:none;">
            View All →
        </a>
    </div>
</div>

{{-- Quick Actions --}}
<h2 class="section-title">Quick Actions</h2>
<div class="quick-actions">
    <a href="{{ route('admin.users.index') }}" class="quick-action">
        <div class="quick-action-icon">👥</div>
        <div class="quick-action-label">Manage Users</div>
    </a>
    <a href="{{ route('admin.sellers.index') }}" class="quick-action">
        <div class="quick-action-icon">🧑‍💼</div>
        <div class="quick-action-label">Manage Sellers</div>
    </a>
    <a href="{{ route('admin.shops.index') }}" class="quick-action">
        <div class="quick-action-icon">🏪</div>
        <div class="quick-action-label">Manage Shops</div>
    </a>
    <a href="{{ route('admin.categories.index') }}" class="quick-action">
        <div class="quick-action-icon">🗂️</div>
        <div class="quick-action-label">Categories</div>
    </a>
    <a href="{{ route('admin.products.index') }}" class="quick-action">
        <div class="quick-action-icon">📦</div>
        <div class="quick-action-label">All Products</div>
    </a>
    <a href="#" class="quick-action">
        <div class="quick-action-icon">📊</div>
        <div class="quick-action-label">Reports</div>
    </a>
</div>

{{-- Two Column Layout --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:8px;">

    {{-- Pending Shops --}}
    <div>
        <h2 class="section-title">Pending Shop Approvals</h2>
        <div class="card" style="padding:0; overflow:hidden;">
            @forelse($recentShops as $shop)
            <div style="display:flex; align-items:center; gap:12px; padding:14px 16px; border-bottom:1px solid var(--border);">
                @if($shop->logo)
                    <img src="{{ asset('storage/' . $shop->logo) }}"
                        style="width:36px; height:36px; border-radius:50%; object-fit:cover;">
                @else
                    <div style="width:36px; height:36px; border-radius:50%; background:rgba(99,102,241,0.12); display:flex; align-items:center; justify-content:center; font-weight:700; color:#a5b4fc; font-size:12px; flex-shrink:0;">
                        {{ strtoupper(substr($shop->name, 0, 2)) }}
                    </div>
                @endif
                <div style="flex:1; min-width:0;">
                    <div style="font-size:13px; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $shop->name }}</div>
                    <div style="font-size:11px; color:var(--text-muted);">{{ $shop->user->name }}</div>
                </div>
                <form method="POST" action="{{ route('admin.shops.approve', $shop) }}">
                    @csrf @method('PATCH')
                    <button type="submit" style="font-size:11px; background:rgba(22,163,74,0.1); color:#86efac; border:none; padding:5px 10px; border-radius:6px; cursor:pointer;">
                        Approve
                    </button>
                </form>
            </div>
            @empty
            <div style="padding:24px; text-align:center; color:var(--text-muted); font-size:13px;">
                <i class="fa fa-circle-check" style="color:#86efac; font-size:20px; display:block; margin-bottom:8px;"></i>
                All shops approved!
            </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Sellers --}}
    <div>
        <h2 class="section-title">Recent Sellers</h2>
        <div class="card" style="padding:0; overflow:hidden;">
            @forelse($recentSellers as $seller)
            <div style="display:flex; align-items:center; gap:12px; padding:14px 16px; border-bottom:1px solid var(--border);">
                <div style="width:36px; height:36px; border-radius:50%; background:rgba(217,119,6,0.12); display:flex; align-items:center; justify-content:center; font-weight:700; color:#fcd34d; font-size:12px; flex-shrink:0;">
                    {{ strtoupper(substr($seller->name, 0, 2)) }}
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="font-size:13px; font-weight:600;">{{ $seller->name }}</div>
                    <div style="font-size:11px; color:var(--text-muted);">
                        {{ $seller->shop ? $seller->shop->name : 'No shop yet' }}
                    </div>
                </div>
                <div style="font-size:11px; color:var(--text-muted);">
                    {{ $seller->created_at->diffForHumans() }}
                </div>
            </div>
            @empty
            <div style="padding:24px; text-align:center; color:var(--text-muted); font-size:13px;">
                No sellers yet.
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
