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
        <div class="stat-value">0</div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fa fa-store"></i></div>
        <div class="stat-value">0</div>
        <div class="stat-label">Total Sellers</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa fa-bangladeshi-taka-sign"></i></div>
        <div class="stat-value">৳0</div>
        <div class="stat-label">Total Revenue</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa fa-box"></i></div>
        <div class="stat-value">0</div>
        <div class="stat-label">Total Orders</div>
    </div>
</div>

{{-- Quick Actions --}}
<h2 class="section-title">Quick Actions</h2>
<div class="quick-actions">
    <a href="#" class="quick-action">
        <div class="quick-action-icon">👥</div>
        <div class="quick-action-label">Manage Users</div>
    </a>
    <a href="#" class="quick-action">
        <div class="quick-action-icon">🏪</div>
        <div class="quick-action-label">Manage Shops</div>
    </a>
    <a href="#" class="quick-action">
        <div class="quick-action-icon">📦</div>
        <div class="quick-action-label">All Orders</div>
    </a>
    <a href="#" class="quick-action">
        <div class="quick-action-icon">🗂️</div>
        <div class="quick-action-label">Categories</div>
    </a>
    <a href="#" class="quick-action">
        <div class="quick-action-icon">💰</div>
        <div class="quick-action-label">Payments</div>
    </a>
    <a href="#" class="quick-action">
        <div class="quick-action-icon">📊</div>
        <div class="quick-action-label">Reports</div>
    </a>
</div>

{{-- Recent Users --}}
<h2 class="section-title">Recent Users</h2>
<div class="card" style="padding: 20px;">
    <div class="empty-state">
        <i class="fa fa-users"></i>
        <p>No users registered yet.</p>
    </div>
</div>

@endsection
