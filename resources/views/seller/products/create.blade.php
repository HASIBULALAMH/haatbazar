@extends('layouts.seller')

@section('title', 'Add Product')

@section('content')

<div class="topbar">
    <div>
        <h1 class="topbar-title">Add Product</h1>
        <p class="topbar-subtitle">List a new product in your shop</p>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('seller.products.index') }}" class="btn-icon">
            <i class="fa fa-arrow-left"></i>
        </a>
    </div>
</div>

<div class="card" style="padding:32px; max-width:680px; margin:0 auto;">

    @if($errors->any())
        <div class="alert-error" style="margin-bottom:20px;">
            @foreach($errors->all() as $error)
                <p><i class="fa fa-circle-exclamation"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Name --}}
        <div class="form-group">
            <label class="form-label">Product Name <span style="color:#fca5a5;">*</span></label>
            <div class="input-wrapper">
                <i class="fa fa-box input-icon"></i>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="form-input @error('name') is-invalid @enderror"
                    placeholder="e.g. Samsung Galaxy S24" required>
            </div>
        </div>

        {{-- Category --}}
        <div class="form-group">
            <label class="form-label">Category <span style="color:#fca5a5;">*</span></label>
            <div class="input-wrapper">
                <i class="fa fa-layer-group input-icon"></i>
                <select name="category_id" class="form-input" style="cursor:pointer;" required>
                    <option value="">— Select Category —</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->parent ? $category->parent->name . ' → ' : '' }}{{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Description --}}
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" rows="4"
                class="form-input @error('description') is-invalid @enderror"
                style="padding:12px 14px; resize:vertical; height:auto;"
                placeholder="Describe your product...">{{ old('description') }}</textarea>
        </div>

        {{-- Price Row --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">Price (৳) <span style="color:#fca5a5;">*</span></label>
                <div class="input-wrapper">
                    <i class="fa fa-bangladeshi-taka-sign input-icon"></i>
                    <input type="number" name="price" value="{{ old('price') }}"
                        class="form-input @error('price') is-invalid @enderror"
                        placeholder="0.00" step="0.01" min="0" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Discount Price (৳) <span style="color:var(--text-muted); font-size:12px;">(optional)</span></label>
                <div class="input-wrapper">
                    <i class="fa fa-bangladeshi-taka-sign input-icon"></i>
                    <input type="number" name="discount_price" value="{{ old('discount_price') }}"
                        class="form-input @error('discount_price') is-invalid @enderror"
                        placeholder="0.00" step="0.01" min="0">
                </div>
            </div>
        </div>

        {{-- Stock --}}
        <div class="form-group">
            <label class="form-label">Stock <span style="color:#fca5a5;">*</span></label>
            <div class="input-wrapper">
                <i class="fa fa-cubes input-icon"></i>
                <input type="number" name="stock" value="{{ old('stock', 0) }}"
                    class="form-input @error('stock') is-invalid @enderror"
                    placeholder="0" min="0" required>
            </div>
        </div>

        {{-- Images --}}
        <div class="form-group">
            <label class="form-label">Product Images <span style="color:var(--text-muted); font-size:12px;">(optional, multiple)</span></label>
            <div style="border:2px dashed rgba(217,119,6,0.3); border-radius:12px; padding:24px; text-align:center; cursor:pointer;"
                 onclick="document.getElementById('images').click()">
                <div id="image-previews" style="display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin-bottom:12px;"></div>
                <i class="fa fa-cloud-arrow-up" style="font-size:28px; color:#fcd34d; margin-bottom:8px;"></i>
                <p style="font-size:13px; color:var(--text-muted);">Click to upload images</p>
                <p style="font-size:11px; color:var(--text-muted);">PNG, JPG (max 2MB each) — First image will be primary</p>
                <input type="file" id="images" name="images[]" accept="image/*"
                    multiple style="display:none;" onchange="previewImages(this)">
            </div>
        </div>

        {{-- Active Toggle --}}
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px; padding:14px 16px; background:rgba(255,255,255,0.03); border:1px solid var(--border); border-radius:12px;">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                {{ old('is_active', true) ? 'checked' : '' }}
                style="width:18px; height:18px; accent-color:var(--seller); cursor:pointer;">
            <label for="is_active" style="cursor:pointer;">
                <div style="font-size:14px; font-weight:500;">Active Listing</div>
                <div style="font-size:12px; color:var(--text-muted);">Product will be visible to buyers</div>
            </label>
        </div>

        <button type="submit" class="btn-submit" style="background:linear-gradient(135deg,#d97706,#b45309);">
            <span class="btn-text">Add Product &nbsp;<i class="fa fa-box"></i></span>
            <span class="btn-loader"><i class="fa fa-spinner fa-spin"></i> &nbsp;Adding...</span>
        </button>

    </form>
</div>

@endsection

@push('scripts')
<script>
function previewImages(input) {
    const container = document.getElementById('image-previews');
    container.innerHTML = '';
    if (input.files && input.files.length > 0) {
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.style.cssText = 'position:relative; display:inline-block;';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = 'width:70px; height:70px; object-fit:cover; border-radius:8px; border:2px solid ' + (index === 0 ? '#d97706' : 'rgba(255,255,255,0.1)') + ';';

                if (index === 0) {
                    const badge = document.createElement('span');
                    badge.textContent = 'Primary';
                    badge.style.cssText = 'position:absolute; bottom:2px; left:0; right:0; text-align:center; font-size:9px; background:rgba(217,119,6,0.9); color:#fff; padding:2px; border-radius:0 0 6px 6px;';
                    wrapper.appendChild(badge);
                }

                wrapper.appendChild(img);
                container.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    }
}
</script>
@endpush
