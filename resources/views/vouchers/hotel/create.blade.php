@extends('layouts.app')
@section('title', 'Hotel Voucher Generator | Alma Travel')
@section('content')
<section class="generator-page">
    <div class="container-xl">
        <div class="page-intro row align-items-end mb-4">
            <div class="col-lg-8">
                <p class="eyebrow">Alma Travel / Consultant workspace</p>
                <h1>Hotel confirmation<br><em>voucher generator</em></h1>
                <p class="lead mb-0">Create a polished, brand-ready confirmation voucher with a live preview before you export or print.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <span class="status-pill"><i></i> Live preview ready</span>
                <p class="small text-muted mb-0">All fields update instantly.<br>Required fields are marked with <span class="required-star">*</span></p>
            </div>
        </div>

        <form id="voucher-form" class="generator-layout" method="POST" action="{{ route('hotel-voucher.store') }}" enctype="multipart/form-data">
            @if($errors->any())
            <div class="alert alert-danger mb-3" style="grid-column: 1 / -1;">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session('duplicate_warning'))
            <div class="alert alert-warning mb-3" style="grid-column: 1 / -1;">
                <strong>⚠ Possible duplicate voucher:</strong> the same guest already has a voucher with overlapping dates:
                <ul class="mb-0 mt-2">
                    @foreach(session('duplicate_warning') as $dupe)
                    <li>{{ $dupe->voucher_number }} — {{ $dupe->check_in_date->format('d M Y') }} → {{ $dupe->check_out_date->format('d M Y') }}</li>
                    @endforeach
                </ul>
                <div class="mt-2">If this is a genuinely different booking, press <strong>Generate</strong> again and it will be created anyway. (If you attached a photo, please attach it again.)</div>
            </div>
            <input type="hidden" name="force_duplicate" value="1">
            @endif


            @csrf
            <input type="hidden" name="voucher_theme" id="voucher_theme" value="{{ $settings->voucher_theme ?? 'premium' }}">
            <section class="form-panel" aria-label="Voucher details">
                <div class="panel-heading">
                    <div><p class="eyebrow mb-1">Step 01 / Booking details</p><h2>Enter the stay information</h2></div>
                    <span class="panel-number">01</span>
                </div>

                                <div class="form-section">
                    <h3><span>01</span> Client &amp; supplier</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="client_type">Client type <span class="required-star">*</span></label>
                            <select class="form-select" id="client_type" name="client_type" required>
                                <option value="">Select client type</option>
                                <option value="credit" {{ old('client_type', ($copy ?? null)?->client_type ?? '') === 'credit' ? 'selected' : '' }}>Credit</option>
                                <option value="cash" {{ old('client_type', ($copy ?? null)?->client_type ?? '') === 'cash' ? 'selected' : '' }}>Cash</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="company_select_wrap">
                            <label class="form-label" for="company_select">Company <span class="required-star">*</span></label>
                            <select class="form-select" id="company_select" name="company_select">
                                <option value="">Select company</option>
                                @foreach (($categories->firstWhere('slug', 'company')?->values ?? collect()) as $value)
                                    <option value="{{ $value->value }}" {{ old('company_select') === $value->value ? 'selected' : '' }}>{{ $value->value }}</option>
                                @endforeach
                            </select>
                            <input class="form-control other-field mt-2 d-none" id="company_select_other" name="company_select_other" value="{{ old('company_select_other') }}" placeholder="Specify company">
                        </div>
                        <div class="col-md-6 d-none" id="company_cash_wrap">
                            <label class="form-label" for="company_name_cash">Company name <span class="required-star">*</span></label>
                            <input class="form-control" id="company_name_cash" name="company_name_cash" value="{{ old('company_name_cash', ($copy ?? null)?->client_type === 'cash' ? ($copy->client_company ?? '') : '') }}" placeholder="e.g. DAN">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="supplier">Supplier <span class="required-star">*</span></label>
                            <input class="form-control" id="supplier" name="supplier" value="{{ old('supplier', ($copy ?? null)?->supplier ?? '') }}" placeholder="e.g. Hotelbeds" required>
                        </div>
                    </div>
                </div>

<div class="form-section">
                    <h3><span>02</span> Guest &amp; hotel</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="guest_name">Guest name <span class="required-star">*</span></label>
                            <input class="form-control" id="guest_name" name="guest_name" value="{{ old('guest_name') }}" placeholder="e.g. Mr. Ahmed Alotaibi" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="confirmation_reference">Confirmation / PNR reference <span class="required-star">*</span></label>
                            <input class="form-control" id="confirmation_reference" name="confirmation_reference" value="{{ old('confirmation_reference') }}" placeholder="e.g. ALM8K2" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="hotel_name">Hotel name <span class="required-star">*</span></label>
                            <input class="form-control" id="hotel_name" name="hotel_name" value="{{ old('hotel_name') }}" placeholder="e.g. Conrad Dubai" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="hotel_address">Hotel address <span class="required-star">*</span></label>
                            <textarea class="form-control" id="hotel_address" name="hotel_address" rows="2" placeholder="Street, area, city, country" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="hotel_location_sub">Hotel location / city <span class="required-star">*</span></label>
                            <input class="form-control" id="hotel_location_sub" name="hotel_location_sub" value="{{ old('hotel_location_sub') }}" placeholder="e.g. Jeddah, Dubai" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="hotel_phone">Hotel phone <span class="required-star">*</span></label>
                            <input class="form-control" id="hotel_phone" name="hotel_phone" value="{{ old('hotel_phone') }}" placeholder="e.g. +966 12 345 6789" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="occupancy">Occupancy / guests</label>
                            <input class="form-control" id="occupancy" name="occupancy" value="{{ old('occupancy', '1 Room | 2 Adults') }}" placeholder="1 Room | 2 Adults">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><span>03</span> Stay dates</h3>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label" for="check_in_date">Check-in date <span class="required-star">*</span></label>
                            <input class="form-control" id="check_in_date" name="check_in_date" type="date" value="{{ old('check_in_date') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="check_in_time">Check-in time</label>
                            <input class="form-control" id="check_in_time" name="check_in_time" type="time" value="{{ old('check_in_time', '15:00') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="check_out_date">Check-out date <span class="required-star">*</span></label>
                            <input class="form-control" id="check_out_date" name="check_out_date" type="date" value="{{ old('check_out_date') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="check_out_time">Check-out time</label>
                            <input class="form-control" id="check_out_time" name="check_out_time" type="time" value="{{ old('check_out_time', '12:00') }}">
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-2">
                            <label class="form-label">Nights</label>
                            <div class="nights-field"><strong id="previewNights">0</strong><span>nights</span></div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><span>04</span> Booking preferences</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="meal_plan">Meal plan <span class="required-star">*</span></label>
                            <select class="form-select" id="meal_plan" name="meal_plan" required>
                                <option value="">Select meal plan</option>
                                @foreach (($categories->firstWhere('slug', 'mealplan')?->values ?? collect()) as $value)
                                    <option value="{{ $value->value }}" {{ old('meal_plan') === $value->value ? 'selected' : '' }}>{{ $value->value }}</option>
                                @endforeach
                            </select>
                            <input class="form-control other-field mt-2 d-none" id="meal_plan_other" name="meal_plan_other" placeholder="Specify meal plan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="room_type">Room type <span class="required-star">*</span></label>
                            <select class="form-select" id="room_type" name="room_type" required>
                                <option value="">Select room type</option>
                                @foreach (($categories->firstWhere('slug', 'roomtype')?->values ?? collect()) as $value)
                                    <option value="{{ $value->value }}" {{ old('room_type') === $value->value ? 'selected' : '' }}>{{ $value->value }}</option>
                                @endforeach
                            </select>
                            <input class="form-control other-field mt-2 d-none" id="room_type_other" name="room_type_other" placeholder="Specify room type">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="cancellation_policy">Cancellation policy <span class="required-star">*</span></label>
                            <select class="form-select" id="cancellation_policy" name="cancellation_policy" required>
                                <option value="">Select cancellation policy</option>
                                @foreach (($categories->firstWhere('slug', 'cancellationpolicy')?->values ?? collect()) as $value)
                                    <option value="{{ $value->value }}" {{ old('cancellation_policy') === $value->value ? 'selected' : '' }}>{{ $value->value }}</option>
                                @endforeach
                            </select>
                            <textarea class="form-control other-field mt-2 d-none" id="cancellation_policy_other" name="cancellation_policy_other" rows="2" placeholder="Specify cancellation policy"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="special_requests">Special requests</label>
                            <textarea class="form-control" id="special_requests" name="special_requests" rows="2" placeholder="e.g. High floor, Non-smoking room, Late check-out">{{ old('special_requests') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><span>05</span> Hotel photo</h3>
                    <div class="photo-control">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="include_hotel_photo" name="include_hotel_photo" value="1" {{ old('include_hotel_photo') ? 'checked' : '' }}>
                            <label class="form-check-label" for="include_hotel_photo">Include hotel photo</label>
                        </div>
                        <label class="file-drop" for="hotel_photo">
                            <span class="file-drop-icon">+</span>
                            <strong>Choose a hotel image</strong>
                            <small>PNG, JPG or WebP up to 5 MB</small>
                        </label>
                        <input class="d-none" id="hotel_photo" name="hotel_photo" type="file" accept="image/png,image/jpeg,image/webp">
                    </div>
                </div>

                <div class="form-section">
                    <h3><span>06</span> Booking consultant</h3>
                    <label class="form-label" for="employee_id">Select consultant</label>
                    <select class="form-select consultant-select" id="employee_id" name="employee_id">
                        <option value="">Select an active employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" data-name="{{ $employee->name }}" data-title="{{ $employee->job_title }}" data-phone="{{ $employee->phone }}" data-email="{{ $employee->email }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }} — {{ $employee->job_title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-actions">
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label class="form-label" for="voucher_theme_select">Voucher Theme</label>
                        <select class="form-select" id="voucher_theme_select" name="voucher_theme" onchange="document.getElementById('voucher_theme').value = this.value; dispatchPreview();">
                            <option value="premium" selected>Alma Premium</option>
                            <option value="premiumred">Alma Premium Red</option>
                            <option value="modern">Alma Modern Premium</option>
                            <option value="maroon">Alma Maroon Elite</option>
                            <option value="crimson">Alma Crimson</option>
                            <option value="slate">Alma Slate</option>                        </select>
                    </div>
                    <button class="btn btn-alma btn-lg generate-voucher-button" type="submit">Generate voucher <span aria-hidden="true">→</span></button>
                    <small>Generating saves a voucher log for admin review.</small>
                </div>
            </section>

            <aside class="preview-panel" aria-label="Live voucher preview">
                <div class="preview-toolbar">
                    <div><p class="eyebrow mb-1">Step 02 / Review</p><h2>Live preview</h2></div>
                    <span class="live-dot"><i></i> Live</span>
                </div>
                <div class="preview-scroll">
                    @include('vouchers.hotel.partials.voucher-layout', [
                        'settings' => $settings,
                        'preview' => true,
                        'voucher' => null,
                        'logoData' => $settings->logoUrl(),
                    ])
                </div>
                <div class="preview-hint"><span>i</span> The preview is the master template used for PDF export.</div>
                <div class="preview-actions">
                    <button class="btn btn-alma w-100" type="button" onclick="window.print()">Print Voucher</button>
                </div>
            </aside>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
(function () {
    var KEY = 'alma-autosave-hotel';
    if (window.location.pathname.indexOf('/copy') !== -1) return;
    var form = document.getElementById('voucher-form');
    if (!form) return;
    function collect() {
        var data = {};
        form.querySelectorAll('input[name], select[name], textarea[name]').forEach(function (el) {
            if (!el.name || el.name === '_token' || el.type === 'file' || el.name === 'force_duplicate') return;
            if (el.type === 'checkbox' || el.type === 'radio') { data[el.name + '::' + el.value] = el.checked; }
            else { data[el.name] = el.value; }
        });
        return data;
    }
    var saveTimer = null;
    function scheduleSave() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(function () { try { localStorage.setItem(KEY, JSON.stringify(collect())); } catch (e) {} }, 400);
    }
    form.addEventListener('input', scheduleSave);
    form.addEventListener('change', scheduleSave);
    var serverState = form.querySelector('.alert-danger') || form.querySelector('.alert-warning');
    var raw = null;
    try { raw = localStorage.getItem(KEY); } catch (e) {}
    if (!serverState && raw) {
        window.addEventListener('load', function () {
            setTimeout(function () {
                var data;
                try { data = JSON.parse(raw); } catch (e) { return; }
                if (!data) return;
                var touched = false;
                Object.keys(data).forEach(function (k) {
                    if (k.indexOf('::') !== -1) {
                        var parts = k.split('::');
                        var cb = form.querySelector('input[name="' + parts[0] + '"][value="' + parts[1] + '"]');
                        if (cb && cb.checked !== data[k]) { cb.checked = data[k]; cb.dispatchEvent(new Event('change', { bubbles: true })); touched = true; }
                        return;
                    }
                    var el = form.querySelector('[name="' + k + '"]');
                    if (el && el.type !== 'file' && String(el.value) !== String(data[k])) {
                        el.value = data[k];
                        el.dispatchEvent(new Event('input', { bubbles: true }));
                        el.dispatchEvent(new Event('change', { bubbles: true }));
                        touched = true;
                    }
                });
                if (touched) {
                    var bar = document.createElement('div');
                    bar.className = 'alert alert-info mb-3';
                    bar.style.gridColumn = '1 / -1';
                    bar.innerHTML = '\uD83D\uDCBE A saved draft was restored. <a href="#" id="alma-discard-draft" style="font-weight:700;">Discard draft</a>';
                    form.prepend(bar);
                    var link = document.getElementById('alma-discard-draft');
                    if (link) link.addEventListener('click', function (ev) { ev.preventDefault(); try { localStorage.removeItem(KEY); } catch (e) {} window.location.href = window.location.pathname; });
                }
            }, 150);
        });
    }
})();
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        try {
            console.log('DOMContentLoaded fired');
            const form = document.getElementById('voucher-form');
            const themeSelect = document.getElementById('voucher_theme_select');
            const themeInput = document.getElementById('voucher_theme');
            const voucherCard = document.getElementById('printVoucher');
            if (themeSelect && themeInput) {
                const validThemes = ['premium', 'premiumred', 'modern', 'maroon', 'crimson', 'slate'];
                const savedTheme = validThemes.includes(themeInput.value) ? themeInput.value : 'premium';
                themeSelect.value = savedTheme;
                themeInput.value = savedTheme;
            }
            if (themeSelect && voucherCard) {
                themeSelect.addEventListener('change', function () {
                    themeInput.value = this.value;
                    voucherCard.classList.remove('theme-modern', 'theme-premium', 'theme-premiumred', 'theme-maroon', 'theme-crimson', 'theme-slate');
                    voucherCard.classList.add('theme-' + this.value);
                    dispatchPreview();
                });
            }
        const checkIn = document.getElementById('check_in_date');
        const checkOut = document.getElementById('check_out_date');
        const photoToggle = document.getElementById('include_hotel_photo');
        const photoInput = document.getElementById('hotel_photo');
        const employeeSelect = document.getElementById('employee_id');
        const nightsField = document.getElementById('previewNights');
        let hotelPhoto = '';

        const formatDate = (value) => {
            if (!value) return 'Select date';
            const date = new Date(value + 'T00:00:00');
            return new Intl.DateTimeFormat('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }).format(date);
        };

        const getOptionValue = (selectId, otherId) => {
            const select = document.getElementById(selectId);
            const other = document.getElementById(otherId);
            return select.value === 'Other' && other.value.trim() ? other.value.trim() : select.value;
        };

        const updateOtherField = (selectId, inputId) => {
            const select = document.getElementById(selectId);
            const input = document.getElementById(inputId);
            if (!select || !input) return;
            input.classList.toggle('d-none', select.value !== 'Other');
            input.required = select.value === 'Other';
        };
        const updateClientType = () => {
            const typeEl = document.getElementById('client_type');
            if (!typeEl) return;
            const t = typeEl.value;
            const isCredit = t === 'credit', isCash = t === 'cash';
            const selWrap = document.getElementById('company_select_wrap');
            const cashWrap = document.getElementById('company_cash_wrap');
            if (selWrap) selWrap.classList.toggle('d-none', !isCredit);
            if (cashWrap) cashWrap.classList.toggle('d-none', !isCash);
            const sel = document.getElementById('company_select');
            const cashInput = document.getElementById('company_name_cash');
            if (sel) sel.required = isCredit;
            if (cashInput) cashInput.required = isCash;
            if (isCredit) updateOtherField('company_select', 'company_select_other');
        };

        const dispatchPreview = () => {
            document.title = 'PREVIEW_RUNNING_' + Date.now();
            document.body.setAttribute('data-preview-status', 'running');
            console.log('dispatchPreview running');
            const previewGuest = document.getElementById('previewGuest');

            const setValue = (id, value, useHtml = false) => {
                try {
                    const elements = document.querySelectorAll('[id="' + id + '"]');
                    if (!elements.length) { console.warn('[preview] missing element: ' + id); return; }
                    elements.forEach(element => {
                        if (useHtml) {
                            element.innerHTML = value;
                        } else {
                            element.textContent = value;
                        }
                    });
                } catch (err) {
                    console.error('[setValue]', id, err);
                }
            };

            let nights = 0;
            try {
                const start = checkIn.value ? new Date(checkIn.value + 'T00:00:00') : null;
                const end = checkOut.value ? new Date(checkOut.value + 'T00:00:00') : null;
                nights = start && end ? Math.max(0, Math.round((end - start) / 86400000)) : 0;
            } catch (err) {
                console.error('[nights]', err);
            }

            try { nightsField.textContent = nights; } catch (err) { console.error('[nightsField]', err); }
            setValue('previewNightsPreview', nights);

            setValue('previewGuest', document.getElementById('guest_name')?.value || 'Guest name');
            setValue('previewReference', document.getElementById('confirmation_reference')?.value || 'Reference');
            setValue('previewCheckIn', formatDate(checkIn?.value) + ' <span style="font-weight: normal; font-size: 9px; color: #666;">(From ' + (document.getElementById('check_in_time')?.value || '15:00') + ')</span>', true);
            setValue('previewCheckOut', formatDate(checkOut?.value) + ' <span style="font-weight: normal; font-size: 9px; color: #666;">(Until ' + (document.getElementById('check_out_time')?.value || '12:00') + ')</span>', true);
            setValue('previewMeal', getOptionValue('meal_plan', 'meal_plan_other'));
            setValue('previewRoom', getOptionValue('room_type', 'room_type_other'));
            setValue('previewPolicy', getOptionValue('cancellation_policy', 'cancellation_policy_other'));
            setValue('previewHotelTitle', document.getElementById('hotel_name')?.value || 'Hotel name');
            setValue('previewHotelLocation', document.getElementById('hotel_location_sub')?.value || '');
            setValue('previewOccupancy', document.getElementById('occupancy')?.value || '1 Room | 2 Adults');
            setValue('previewSpecialRequests', document.getElementById('special_requests')?.value || 'Special requests');
            const option = employeeSelect.selectedOptions[0] || {};
            setValue('previewConsultantName', option.dataset?.name || 'Alma Travel Team');
            setValue('previewConsultantEmail', option.dataset?.email || 'Booking support');
            setValue('previewConsultantPhone', option.dataset?.phone || ({!! json_encode($settings->phone ?? '+966 920022050') !!}));
            setValue('previewHotelAddress', document.getElementById('hotel_address')?.value || 'Hotel address');
            setValue('previewHotelPhone', document.getElementById('hotel_phone')?.value || '—');

            try {
                const hotelPhotoElement = document.getElementById('previewHotelPhoto');
                const photoPlaceholder = document.getElementById('previewPhotoPlaceholder');
                const showHotelPhoto = document.getElementById('include_hotel_photo')?.checked && !!hotelPhoto;
                if (hotelPhotoElement) {
                    hotelPhotoElement.src = showHotelPhoto ? hotelPhoto : hotelPhotoElement.dataset.heroImage;
                    hotelPhotoElement.classList.remove('d-none');
                }
                if (photoPlaceholder) photoPlaceholder.classList.add('d-none');
            } catch (err) { console.error('[hotelPhoto]', err); }

            try {
                const consultantContact = [option.dataset?.phone, option.dataset?.email].filter(Boolean).join(' · ');
                window.dispatchEvent(new CustomEvent('voucherPreviewUpdate', {
                    detail: {
                        guestName: document.getElementById('guest_name')?.value || 'Guest name',
                        confirmationReference: document.getElementById('confirmation_reference')?.value || 'Reference',
                        checkIn: formatDate(checkIn?.value),
                        checkOut: formatDate(checkOut?.value),
                        nights: `${nights} night${nights === 1 ? '' : 's'}`,
                        mealPlan: getOptionValue('meal_plan', 'meal_plan_other'),
                        roomType: getOptionValue('room_type', 'room_type_other'),
                        cancellationPolicy: getOptionValue('cancellation_policy', 'cancellation_policy_other'),
                        hotelName: document.getElementById('hotel_name')?.value || 'Hotel name',
                        hotelPhoto: !!hotelPhoto,
                        consultantName: option.dataset?.name || 'Alma Travel Team',
                        consultantContact: consultantContact || 'Phone · Email',
                    },
                }));
            } catch (err) { console.error('[voucherPreviewUpdate]', err); }
        };

        window.dispatchPreview = dispatchPreview;
        console.log('ATTACHING LISTENERS');
        form.addEventListener('input', () => {
            updateClientType();
            updateOtherField('meal_plan', 'meal_plan_other');
            updateOtherField('room_type', 'room_type_other');
            updateOtherField('cancellation_policy', 'cancellation_policy_other');
            updateClientType();
            dispatchPreview();
        });
        form.addEventListener('change', dispatchPreview);
        checkIn.addEventListener('change', dispatchPreview);
        checkOut.addEventListener('change', dispatchPreview);
        console.log('LISTENERS ATTACHED');
        window.addEventListener('voucherThemeChange', (event) => {
            document.getElementById('voucher_theme').value = event.detail.themeName;
        });
        photoInput.addEventListener('change', () => {
            const file = photoInput.files[0];
            if (!file) {
                hotelPhoto = '';
                dispatchPreview();
                return;
            }
            const reader = new FileReader();
            reader.addEventListener('load', () => {
                hotelPhoto = reader.result;
                photoToggle.checked = true;
                dispatchPreview();
            });
            reader.readAsDataURL(file);
        });
        console.log('CALLING dispatchPreview');
        dispatchPreview();
        } catch (err) {
            console.error('DOMContentLoaded FATAL:', err);
            document.body.insertAdjacentHTML('afterbegin', '<div id="js-error" style="position:fixed;top:0;left:0;right:0;background:#ff0000;color:#fff;padding:20px;z-index:99999;font-family:monospace;font-size:14px;border-bottom:3px solid #000"><strong>JavaScript Error:</strong><br>' + (err.message || 'Unknown') + '<br><strong>Stack:</strong><br><pre style="white-space:pre-wrap;max-height:200px;overflow:auto">' + (err.stack || 'No stack') + '</pre></div>');
        }
    });
</script>
@endsection
