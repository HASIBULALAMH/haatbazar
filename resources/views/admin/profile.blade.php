@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')

<div class="topbar">
    <div>
        <h1 class="topbar-title">My Profile</h1>
        <p class="topbar-subtitle">Update your personal information</p>
    </div>
</div>

@if(session('success'))
    <div class="alert-success" style="margin-bottom:20px;">
        <i class="fa fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:start;">

    {{-- Profile Update --}}
    <div class="card" style="padding:28px;">
        <h3 style="font-size:16px; font-weight:700; margin-bottom:24px; display:flex; align-items:center; gap:8px;">
            <i class="fa fa-user" style="color:#a5b4fc;"></i> Personal Info
        </h3>

        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PATCH')

            {{-- Avatar --}}
            <div style="display:flex; align-items:center; gap:16px; margin-bottom:24px;">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                        style="width:72px; height:72px; border-radius:50%; object-fit:cover; border:2px solid var(--border);"
                        id="avatar-preview">
                @else
                    <div style="width:72px; height:72px; border-radius:50%; background:rgba(99,102,241,0.12); display:flex; align-items:center; justify-content:center; font-weight:700; color:#a5b4fc; font-size:24px; border:2px solid var(--border);"
                        id="avatar-placeholder">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                @endif
                <div>
                    <label for="avatar-input"
                        style="display:inline-flex; align-items:center; gap:6px; padding:8px 14px; background:rgba(99,102,241,0.1); color:#a5b4fc; border-radius:8px; font-size:12px; cursor:pointer; font-weight:600;">
                        <i class="fa fa-camera"></i> Change Photo
                    </label>
                    <input type="file" name="avatar" id="avatar-input" accept="image/*"
                        style="display:none;" onchange="previewAvatar(this)">
                    <p style="font-size:11px; color:var(--text-muted); margin-top:6px;">Max 2MB. JPG, PNG</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Full Name <span style="color:#fca5a5;">*</span></label>
                <div class="input-wrapper">
                    <i class="fa fa-user input-icon"></i>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                        class="form-input @error('name') is-invalid @enderror" required>
                </div>
                @error('name')<p style="color:#fca5a5; font-size:12px; margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-wrapper">
                    <i class="fa fa-envelope input-icon"></i>
                    <input type="email" value="{{ Auth::user()->email }}"
                        class="form-input" disabled style="opacity:0.5; cursor:not-allowed;">
                </div>
                <p style="font-size:11px; color:var(--text-muted); margin-top:4px;">Email cannot be changed.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Phone <span style="color:#fca5a5;">*</span></label>
                <div class="input-wrapper">
                    <i class="fa fa-phone input-icon"></i>
                    <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                        class="form-input @error('phone') is-invalid @enderror" required>
                </div>
                @error('phone')<p style="color:#fca5a5; font-size:12px; margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn-submit" style="background:linear-gradient(135deg,#6366f1,#4f46e5);">
                <span class="btn-text">Save Changes &nbsp;<i class="fa fa-floppy-disk"></i></span>
                <span class="btn-loader"><i class="fa fa-spinner fa-spin"></i> &nbsp;Saving...</span>
            </button>
        </form>
    </div>

    {{-- Password Update --}}
    <div class="card" style="padding:28px;">
        <h3 style="font-size:16px; font-weight:700; margin-bottom:24px; display:flex; align-items:center; gap:8px;">
            <i class="fa fa-lock" style="color:#a5b4fc;"></i> Change Password
        </h3>

        <form method="POST" action="{{ route('admin.password.update') }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Current Password <span style="color:#fca5a5;">*</span></label>
                <div class="input-wrapper">
                    <i class="fa fa-lock input-icon"></i>
                    <input type="password" name="current_password"
                        class="form-input @error('current_password') is-invalid @enderror"
                        placeholder="Enter current password" required>
                </div>
                @error('current_password')<p style="color:#fca5a5; font-size:12px; margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">New Password <span style="color:#fca5a5;">*</span></label>
                <div class="input-wrapper">
                    <i class="fa fa-lock input-icon"></i>
                    <input type="password" name="password"
                        class="form-input @error('password') is-invalid @enderror"
                        placeholder="Minimum 8 characters" required>
                </div>
                @error('password')<p style="color:#fca5a5; font-size:12px; margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password <span style="color:#fca5a5;">*</span></label>
                <div class="input-wrapper">
                    <i class="fa fa-lock input-icon"></i>
                    <input type="password" name="password_confirmation"
                        class="form-input"
                        placeholder="Repeat new password" required>
                </div>
            </div>

            <button type="submit" class="btn-submit" style="background:linear-gradient(135deg,#d97706,#b45309);">
                <span class="btn-text">Update Password &nbsp;<i class="fa fa-key"></i></span>
                <span class="btn-loader"><i class="fa fa-spinner fa-spin"></i> &nbsp;Updating...</span>
            </button>
        </form>
    </div>

</div>

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const placeholder = document.getElementById('avatar-placeholder');
            let preview = document.getElementById('avatar-preview');
            if (placeholder) {
                placeholder.outerHTML = `<img src="${e.target.result}" id="avatar-preview"
                    style="width:72px; height:72px; border-radius:50%; object-fit:cover; border:2px solid var(--border);">`;
            } else if (preview) {
                preview.src = e.target.result;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@endsection
