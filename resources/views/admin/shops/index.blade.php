@extends('layouts.admin')

@section('title', 'Shops')

@section('content')

<div class="topbar">
    <div>
        <h1 class="topbar-title">Shops</h1>
        <p class="topbar-subtitle">Manage and approve seller shops</p>
    </div>
    <div class="topbar-actions">
        <span style="font-size:13px; color:var(--text-muted);">
            Total: {{ $shops->count() }} |
            <span style="color:#86efac;">Approved: {{ $shops->where('is_approved', true)->count() }}</span> |
            <span style="color:#fcd34d;">Pending: {{ $shops->where('is_approved', false)->count() }}</span>
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
                <th>Shop</th>
                <th>Owner</th>
                <th>Products</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shops as $shop)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:12px;">
                        @if($shop->logo)
                            <img src="{{ asset('storage/' . $shop->logo) }}"
                                style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid rgba(99,102,241,0.3);">
                        @else
                            <div style="width:40px; height:40px; border-radius:50%; background:rgba(99,102,241,0.12); display:flex; align-items:center; justify-content:center; font-family:'Playfair Display',serif; font-weight:700; color:#a5b4fc; font-size:14px;">
                                {{ strtoupper(substr($shop->name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <div style="font-weight:600;">{{ $shop->name }}</div>
                            <div style="font-size:11px; color:var(--text-muted);">{{ $shop->slug }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="font-size:13px;">{{ $shop->user->name }}</div>
                    <div style="font-size:11px; color:var(--text-muted);">{{ $shop->user->email }}</div>
                </td>
                <td>
                    <span style="color:#a5b4fc; font-weight:600;">{{ $shop->products()->count() }}</span>
                </td>
                <td>
                    @if($shop->is_approved)
                        <span class="badge" style="background:rgba(22,163,74,0.1); color:#86efac;">
                            <i class="fa fa-circle-check"></i> Approved
                        </span>
                    @else
                        <span class="badge" style="background:rgba(245,158,11,0.1); color:#fcd34d;">
                            <i class="fa fa-clock"></i> Pending
                        </span>
                    @endif
                </td>
                <td style="font-size:12px; color:var(--text-muted);">
                    {{ $shop->created_at->format('d M Y') }}
                </td>
                <td>
                    <div style="display:flex; gap:8px;">
                        @if(!$shop->is_approved)
                            <form method="POST" action="{{ route('admin.shops.approve', $shop) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    style="padding:6px 12px; background:rgba(22,163,74,0.1); color:#86efac; border-radius:8px; font-size:12px; border:none; cursor:pointer;">
                                    <i class="fa fa-circle-check"></i> Approve
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.shops.reject', $shop) }}"
                                  onsubmit="return confirm('Reject this shop?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    style="padding:6px 12px; background:rgba(239,68,68,0.1); color:#fca5a5; border-radius:8px; font-size:12px; border:none; cursor:pointer;">
                                    <i class="fa fa-ban"></i> Reject
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <i class="fa fa-store"></i>
                        <p>No shops yet.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
