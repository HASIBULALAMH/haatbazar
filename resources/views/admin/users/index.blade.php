@extends('layouts.admin')

@section('title', 'Users')

@section('content')

<div class="topbar">
    <div>
        <h1 class="topbar-title">Buyers</h1>
        <p class="topbar-subtitle">Manage all registered buyers</p>
    </div>
    <div class="topbar-actions">
        <span style="font-size:13px; color:var(--text-muted);">
            Total: {{ $users->count() }} |
            <span style="color:#86efac;">Active: {{ $users->where('is_active', true)->count() }}</span> |
            <span style="color:#fca5a5;">Inactive: {{ $users->where('is_active', false)->count() }}</span>
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
                <th>User</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Joined</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                style="width:36px; height:36px; border-radius:50%; object-fit:cover;">
                        @else
                            <div style="width:36px; height:36px; border-radius:50%; background:rgba(99,102,241,0.12); display:flex; align-items:center; justify-content:center; font-weight:700; color:#a5b4fc; font-size:13px;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif
                        <span style="font-weight:600;">{{ $user->name }}</span>
                    </div>
                </td>
                <td style="font-size:13px; color:var(--text-muted);">{{ $user->email }}</td>
                <td style="font-size:13px;">{{ $user->phone ?? '—' }}</td>
                <td style="font-size:12px; color:var(--text-muted);">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    @if($user->is_active)
                        <span class="badge" style="background:rgba(22,163,74,0.1); color:#86efac;">Active</span>
                    @else
                        <span class="badge" style="background:rgba(239,68,68,0.1); color:#fca5a5;">Inactive</span>
                    @endif
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            style="padding:6px 12px; background:{{ $user->is_active ? 'rgba(239,68,68,0.1)' : 'rgba(22,163,74,0.1)' }}; color:{{ $user->is_active ? '#fca5a5' : '#86efac' }}; border-radius:8px; font-size:12px; border:none; cursor:pointer;">
                            <i class="fa fa-{{ $user->is_active ? 'ban' : 'circle-check' }}"></i>
                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <i class="fa fa-users"></i>
                        <p>No buyers yet.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
