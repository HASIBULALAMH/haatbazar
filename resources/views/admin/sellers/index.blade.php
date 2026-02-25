@extends('layouts.admin')

@section('title', 'Sellers')

@section('content')

<div class="topbar">
    <div>
        <h1 class="topbar-title">Sellers</h1>
        <p class="topbar-subtitle">Manage all registered sellers</p>
    </div>
    <div class="topbar-actions">
        <span style="font-size:13px; color:var(--text-muted);">
            Total: {{ $sellers->count() }} |
            <span style="color:#86efac;">Active: {{ $sellers->where('is_active', true)->count() }}</span> |
            <span style="color:#fca5a5;">Inactive: {{ $sellers->where('is_active', false)->count() }}</span>
        </span>
    </div>
</div>

@if(session('success'))
    <div class="alert-success" style="margin-bottom:20px;">
        <i class="fa fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

<div class="card" style="padding:0; overflow:hidden;">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Seller</th>
                <th>Email</th>
                <th>Shop</th>
                <th>Products</th>
                <th>Joined</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sellers as $seller)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        @if($seller->avatar)
                            <img src="{{ asset('storage/' . $seller->avatar) }}"
                                style="width:36px; height:36px; border-radius:50%; object-fit:cover;">
                        @else
                            <div style="width:36px; height:36px; border-radius:50%; background:rgba(217,119,6,0.12); display:flex; align-items:center; justify-content:center; font-weight:700; color:#fcd34d; font-size:13px;">
                                {{ strtoupper(substr($seller->name, 0, 2)) }}
                            </div>
                        @endif
                        <span style="font-weight:600;">{{ $seller->name }}</span>
                    </div>
                </td>
                <td style="font-size:13px; color:var(--text-muted);">{{ $seller->email }}</td>
                <td>
                    @if($seller->shop)
                        <div style="font-size:13px;">{{ $seller->shop->name }}</div>
                        @if($seller->shop->is_approved)
                            <span style="font-size:11px; color:#86efac;"><i class="fa fa-circle-check"></i> Approved</span>
                        @else
                            <span style="font-size:11px; color:#fcd34d;"><i class="fa fa-clock"></i> Pending</span>
                        @endif
                    @else
                        <span style="font-size:12px; color:var(--text-muted);">No shop</span>
                    @endif
                </td>
                <td>
                    <span style="color:#a5b4fc; font-weight:600;">
                        {{ $seller->shop ? $seller->shop->products()->count() : 0 }}
                    </span>
                </td>
                <td style="font-size:12px; color:var(--text-muted);">{{ $seller->created_at->format('d M Y') }}</td>
                <td>
                    @if($seller->is_active)
                        <span class="badge" style="background:rgba(22,163,74,0.1); color:#86efac;">Active</span>
                    @else
                        <span class="badge" style="background:rgba(239,68,68,0.1); color:#fca5a5;">Inactive</span>
                    @endif
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.sellers.toggle', $seller) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            style="padding:6px 12px; background:{{ $seller->is_active ? 'rgba(239,68,68,0.1)' : 'rgba(22,163,74,0.1)' }}; color:{{ $seller->is_active ? '#fca5a5' : '#86efac' }}; border-radius:8px; font-size:12px; border:none; cursor:pointer;">
                            <i class="fa fa-{{ $seller->is_active ? 'ban' : 'circle-check' }}"></i>
                            {{ $seller->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i class="fa fa-store"></i>
                        <p>No sellers yet.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
