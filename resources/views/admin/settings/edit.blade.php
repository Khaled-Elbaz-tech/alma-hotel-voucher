@extends('layouts.admin')
@section('page-title', 'Company settings')
@section('content')
<div class="admin-card narrow-card">
    <div class="admin-card-heading"><p class="eyebrow mb-1">Brand master</p><h2>Company settings</h2><p class="text-muted mb-0">These details appear across every generated voucher.</p></div>
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="setting-brand-row">
            <div class="brand-preview"><div class="brand-preview-box">@if ($setting->logoUrl())<img src="{{ $setting->logoUrl() }}" alt="Company logo">@else<span>AT</span>@endif</div><div><strong>Official logo</strong><span>PNG, JPG, WebP or SVG up to 5 MB</span></div></div>
            <label class="btn btn-outline-dark" for="logo">Upload new logo</label><input class="d-none" id="logo" name="logo" type="file" accept=".svg,.png,.jpg,.jpeg,.webp">
        </div>
        <div class="row g-3 mt-1">
            <div class="col-md-8"><label class="form-label" for="company_name">Company name</label><input class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $setting->company_name) }}" required></div>
            <div class="col-md-4"><label class="form-label" for="voucher_theme">Voucher theme</label><select class="form-select" id="voucher_theme" name="voucher_theme" required><option value="premium" {{ old('voucher_theme', $setting->voucher_theme) === 'premium' ? 'selected' : '' }}>Alma Premium</option><option value="modern" {{ old('voucher_theme', $setting->voucher_theme) === 'modern' ? 'selected' : '' }}>Alma Modern Premium</option><option value="maroon" {{ old('voucher_theme', $setting->voucher_theme) === 'maroon' ? 'selected' : '' }}>Alma Maroon Elite</option><option value="crimson" {{ old('voucher_theme', $setting->voucher_theme) === 'crimson' ? 'selected' : '' }}>Alma Crimson</option><option value="slate" {{ old('voucher_theme', $setting->voucher_theme) === 'slate' ? 'selected' : '' }}>Alma Slate</option></select></div>
            <div class="col-md-4"><label class="form-label" for="tagline">Tagline</label><input class="form-control" id="tagline" name="tagline" value="{{ old('tagline', $setting->tagline) }}" required></div>
            <div class="col-12"><label class="form-label" for="address">Company address</label><textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $setting->address) }}</textarea></div>
            <div class="col-md-4"><label class="form-label" for="brand_primary_color">Primary color</label><div class="color-field"><input class="form-control" id="brand_primary_color" name="brand_primary_color" value="{{ old('brand_primary_color', $setting->brand_primary_color) }}" required><input type="color" value="{{ $setting->brand_primary_color }}" aria-label="Pick primary color"></div></div>
            <div class="col-md-4"><label class="form-label" for="brand_dark_red">Dark red</label><div class="color-field"><input class="form-control" id="brand_dark_red" name="brand_dark_red" value="{{ old('brand_dark_red', $setting->brand_dark_red) }}" required><input type="color" value="{{ $setting->brand_dark_red }}" aria-label="Pick dark red"></div></div>
            <div class="col-md-4"><label class="form-label" for="brand_gold">Gold accent</label><div class="color-field"><input class="form-control" id="brand_gold" name="brand_gold" type="text" value="{{ old('brand_gold', $setting->brand_gold) }}" required><input type="color" value="{{ $setting->brand_gold }}" aria-label="Pick gold accent"></div></div>
            <div class="col-md-4"><label class="form-label" for="brand_surface_light">Light surface</label><div class="color-field"><input class="form-control" id="brand_surface_light" name="brand_surface_light" type="text" value="{{ old('brand_surface_light', $setting->brand_surface_light) }}" required><input type="color" value="{{ $setting->brand_surface_light }}" aria-label="Pick light surface"></div></div>
            <div class="col-md-4"><label class="form-label" for="brand_surface_dark">Dark surface</label><div class="color-field"><input class="form-control" id="brand_surface_dark" name="brand_surface_dark" type="text" value="{{ old('brand_surface_dark', $setting->brand_surface_dark) }}" required><input type="color" value="{{ $setting->brand_surface_dark }}" aria-label="Pick dark surface"></div></div>
        </div>
        <div class="form-actions admin-form-actions"><button class="btn btn-alma" type="submit">Save company settings</button></div>
    </form>
</div>
@endsection
@section('scripts')
<script>
    document.querySelectorAll('.color-field input[type="color"]').forEach((picker) => {
        const text = picker.parentElement.querySelector('input[type="text"], input:not([type])');
        picker.addEventListener('input', () => { text.value = picker.value.toUpperCase(); });
    });
</script>
@endsection
