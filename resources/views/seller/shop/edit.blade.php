@extends('layouts.seller')

@section('title', 'Edit Shop')

@section('content')

<div class="topbar">
    <div>
        <h1 class="topbar-title">My Shop 🏪</h1>
        <p class="topbar-subtitle">Manage your shop details</p>
    </div>
    <div class="topbar-actions">
        @if($shop->is_approved)
            <span style="display:flex; align-items:center; gap:6px; background:rgba(22,163,74,0.1); border:1px solid rgba(22,163,74,0.3); color:var(--primary-light); padding:6px 14px; border-radius:20px; font-size:12px; font-weight:600;">
                <i class="fa fa-circle-check"></i> Approved
            </span>
        @else
            <span style="display:flex; align-items:center; gap:6px; background:rgba(245,158,11,0.1); border:1px solid rgba(245,158,11,0.3); color:#fcd34d; padding:6px 14px; border-radius:20px; font-size:12px; font-weight:600;">
                <i class="fa fa-clock"></i> Pending Approval
            </span>
        @endif
    </div>
</div>

{{-- Banner Preview --}}
@if($shop->banner)
<div style="margin-bottom:24px; border-radius:16px; overflow:hidden; height:180px;">
    <img src="{{ asset('storage/' . $shop->banner) }}" alt="banner"
        style="width:100%; height:100%; object-fit:cover;">
</div>
@endif

<div class="card" style="padding:32px; max-width:640px; margin:0 auto;">

    @if(session('success'))
        <div class="alert-success" style="margin-bottom:20px;">
            <i class="fa fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error" style="margin-bottom:20px;">
            @foreach($errors->all() as $error)
                <p><i class="fa fa-circle-exclamation"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Logo --}}
    <div style="text-align:center; margin-bottom:28px; padding-bottom:28px; border-bottom:1px solid var(--border);">
        <div style="position:relative; display:inline-block;">
            @if($shop->logo)
                <img src="{{ asset('storage/' . $shop->logo) }}" alt="logo"
                    id="logo-img"
                    style="width:90px; height:90px; border-radius:50%; object-fit:cover; border:3px solid var(--seller);">
            @else
                <div id="logo-img" style="width:90px; height:90px; border-radius:50%; background:linear-gradient(135deg,var(--seller),var(--seller-dark)); display:flex; align-items:center; justify-content:center; font-family:'Playfair Display',serif; font-size:28px; font-weight:700; color:#fff; border:3px solid rgba(217,119,6,0.4);">
                    {{ strtoupper(substr($shop->name, 0, 2)) }}
                </div>
            @endif
        </div>
        <h2 style="font-family:'Playfair Display',serif; font-size:20px; font-weight:700; margin-top:12px;">{{ $shop->name }}</h2>
        <p style="font-size:13px; color:var(--text-muted);">{{ $shop->slug }}</p>
    </div>

    <form method="POST" action="{{ route('seller.shop.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label class="form-label">Shop Name <span style="color:#fca5a5;">*</span></label>
            <div class="input-wrapper">
                <i class="fa fa-store input-icon"></i>
                <input type="text" name="name" value="{{ old('name', $shop->name) }}"
                    class="form-input @error('name') is-invalid @enderror"
                    placeholder="Shop name" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" rows="4"
                class="form-input @error('description') is-invalid @enderror"
                style="padding:12px 14px; resize:vertical; height:auto;"
                placeholder="Tell customers about your shop...">{{ old('description', $shop->description) }}</textarea>
        </div>

        {{-- Logo Upload --}}
        <div class="form-group">
            <label class="form-label">Change Logo</label>
            <div style="border:2px dashed rgba(217,119,6,0.3); border-radius:12px; padding:20px; text-align:center; cursor:pointer;"
                 onclick="document.getElementById('logo').click()">
                <i class="fa fa-cloud-arrow-up" style="font-size:24px; color:#fcd34d; margin-bottom:8px;"></i>
                <p style="font-size:13px; color:var(--text-muted);">Click to change logo</p>
                <input type="file" id="logo" name="logo" accept="image/*" style="display:none;">
            </div>
        </div>

        {{-- Banner Upload --}}
        <div class="form-group">
            <label class="form-label">Change Banner</label>
            <div style="border:2px dashed rgba(217,119,6,0.3); border-radius:12px; padding:20px; text-align:center; cursor:pointer;"
                 onclick="document.getElementById('banner').click()">
                <i class="fa fa-panorama" style="font-size:24px; color:#fcd34d; margin-bottom:8px;"></i>
                <p style="font-size:13px; color:var(--text-muted);">Click to change banner</p>
                <input type="file" id="banner" name="banner" accept="image/*" style="display:none;">
            </div>
        </div>

        <button type="submit" class="btn-submit" style="background:linear-gradient(135deg,#d97706,#b45309);">
            <span class="btn-text">Update Shop &nbsp;<i class="fa fa-floppy-disk"></i></span>
            <span class="btn-loader"><i class="fa fa-spinner fa-spin"></i> &nbsp;Updating...</span>
        </button>

    </form>
</div>

@endsection
