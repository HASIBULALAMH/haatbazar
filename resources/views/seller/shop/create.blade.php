@extends('layouts.seller')

@section('title', 'Create Shop')

@section('content')

<div class="topbar">
    <div>
        <h1 class="topbar-title">Create Your Shop 🏪</h1>
        <p class="topbar-subtitle">Setup your shop to start selling on HaatBazar</p>
    </div>
</div>

<div class="card" style="padding:32px; max-width:640px; margin:0 auto;">

    @if($errors->any())
        <div class="alert-error" style="margin-bottom:20px;">
            @foreach($errors->all() as $error)
                <p><i class="fa fa-circle-exclamation"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('seller.shop.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Shop Name --}}
        <div class="form-group">
            <label class="form-label">Shop Name <span style="color:#fca5a5;">*</span></label>
            <div class="input-wrapper">
                <i class="fa fa-store input-icon"></i>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="form-input @error('name') is-invalid @enderror"
                    placeholder="e.g. Hasib's Electronics" required>
            </div>
        </div>

        {{-- Description --}}
        <div class="form-group">
            <label class="form-label">Description <span style="color:var(--text-muted); font-size:12px;">(optional)</span></label>
            <textarea name="description" rows="4"
                class="form-input @error('description') is-invalid @enderror"
                style="padding:12px 14px; resize:vertical; height:auto;"
                placeholder="Tell customers about your shop...">{{ old('description') }}</textarea>
        </div>

        {{-- Logo --}}
        <div class="form-group">
            <label class="form-label">Shop Logo <span style="color:var(--text-muted); font-size:12px;">(optional)</span></label>
            <div style="border:2px dashed rgba(217,119,6,0.3); border-radius:12px; padding:24px; text-align:center; cursor:pointer;"
                 onclick="document.getElementById('logo').click()">
                <div id="logo-preview" style="display:none; margin-bottom:12px;">
                    <img id="logo-img" src="" alt="logo"
                        style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:3px solid var(--seller);">
                </div>
                <div id="logo-placeholder">
                    <i class="fa fa-image" style="font-size:28px; color:#fcd34d; margin-bottom:8px;"></i>
                    <p style="font-size:13px; color:var(--text-muted);">Click to upload logo</p>
                    <p style="font-size:11px; color:var(--text-muted);">Square image recommended (max 2MB)</p>
                </div>
                <input type="file" id="logo" name="logo" accept="image/*" style="display:none;"
                    onchange="previewImage(this, 'logo-img', 'logo-preview', 'logo-placeholder')">
            </div>
        </div>

        {{-- Banner --}}
        <div class="form-group">
            <label class="form-label">Shop Banner <span style="color:var(--text-muted); font-size:12px;">(optional)</span></label>
            <div style="border:2px dashed rgba(217,119,6,0.3); border-radius:12px; padding:24px; text-align:center; cursor:pointer;"
                 onclick="document.getElementById('banner').click()">
                <div id="banner-preview" style="display:none; margin-bottom:12px;">
                    <img id="banner-img" src="" alt="banner"
                        style="width:100%; max-height:120px; border-radius:8px; object-fit:cover;">
                </div>
                <div id="banner-placeholder">
                    <i class="fa fa-panorama" style="font-size:28px; color:#fcd34d; margin-bottom:8px;"></i>
                    <p style="font-size:13px; color:var(--text-muted);">Click to upload banner</p>
                    <p style="font-size:11px; color:var(--text-muted);">Wide image recommended (max 4MB)</p>
                </div>
                <input type="file" id="banner" name="banner" accept="image/*" style="display:none;"
                    onchange="previewImage(this, 'banner-img', 'banner-preview', 'banner-placeholder')">
            </div>
        </div>

        <div style="background:rgba(217,119,6,0.08); border:1px solid rgba(217,119,6,0.2); border-radius:12px; padding:14px 16px; margin-bottom:20px; display:flex; gap:12px; align-items:flex-start;">
            <i class="fa fa-circle-info" style="color:#fcd34d; margin-top:2px;"></i>
            <p style="font-size:13px; color:var(--text-muted); line-height:1.6;">Your shop will be reviewed by admin before going live. This usually takes 24 hours.</p>
        </div>

        <button type="submit" class="btn-submit" style="background:linear-gradient(135deg,#d97706,#b45309);">
            <span class="btn-text">Create Shop &nbsp;<i class="fa fa-store"></i></span>
            <span class="btn-loader"><i class="fa fa-spinner fa-spin"></i> &nbsp;Creating...</span>
        </button>

    </form>
</div>

@endsection

@push('scripts')
<script>
function previewImage(input, imgId, previewId, placeholderId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(imgId).src = e.target.result;
            document.getElementById(previewId).style.display = 'block';
            document.getElementById(placeholderId).style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
