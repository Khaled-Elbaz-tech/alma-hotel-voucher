@extends('layouts.app')
@section('title', 'Car Rental Voucher Generator | Alma Travel')
@section('content')
<section class="generator-page">
    <div class="container-xl">
        <div class="page-intro row align-items-end mb-4">
            <div class="col-lg-8">
                <p class="eyebrow">Alma Travel / Consultant workspace</p>
                <h1>Car rental confirmation<br><em>voucher generator</em></h1>
                <p class="lead mb-0">Create a polished, brand-ready confirmation voucher with a live preview before you export or print.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <span class="status-pill"><i></i> Live preview ready</span>
                <p class="small text-muted mb-0">All fields update instantly.<br>Required fields are marked with <span class="required-star">*</span></p>
            </div>
        </div>

        <form id="voucher-form" class="generator-layout" method="POST" action="{{ route('car-rental-voucher.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="voucher_theme" id="voucher_theme" value="{{ $settings->voucher_theme ?? 'classic' }}">
            <section class="form-panel" aria-label="Voucher details">
                <div class="panel-heading">
                    <div><p class="eyebrow mb-1">Step 01 / Booking details</p><h2>Enter the rental information</h2></div>
                    <span class="panel-number">01</span>
                </div>

                <!-- Booking Details -->
                <div class="form-section">
                    <h3><span>01</span> Booking details</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="guest_name">Guest name <span class="required-star">*</span></label>
                            <input class="form-control" id="guest_name" name="guest_name" value="{{ old('guest_name') }}" placeholder="e.g. Mr. Ahmed Alotaibi" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="booking_number">Booking number</label>
                            <input class="form-control" id="booking_number" name="booking_number" value="{{ old('booking_number') }}" placeholder="e.g. BK123456">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="confirmation_reference">Confirmation / PNR</label>
                            <input class="form-control" id="confirmation_reference" name="confirmation_reference" value="{{ old('confirmation_reference') }}" placeholder="e.g. ALM8K2">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="contact_number">Contact number</label>
                            <input class="form-control" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" placeholder="e.g. +966 50 123 4567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="id_passport_number">ID / Passport number</label>
                            <input class="form-control" id="id_passport_number" name="id_passport_number" value="{{ old('id_passport_number') }}" placeholder="e.g. 1234567890">
                        </div>
                    </div>
                </div>

                <!-- Pickup Details -->
                <div class="form-section">
                    <h3><span>02</span> Pickup details</h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="pickup_date">Pickup date <span class="required-star">*</span></label>
                            <input class="form-control" id="pickup_date" name="pickup_date" type="date" value="{{ old('pickup_date') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="pickup_time">Pickup time <span class="required-star">*</span></label>
                            <input class="form-control" id="pickup_time" name="pickup_time" type="time" value="{{ old('pickup_time', '10:00') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="pickup_terminal">Pickup terminal</label>
                            <input class="form-control" id="pickup_terminal" name="pickup_terminal" value="{{ old('pickup_terminal') }}" placeholder="e.g. Terminal 1">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="pickup_location">Pickup location <span class="required-star">*</span></label>
                            <input class="form-control" id="pickup_location" name="pickup_location" value="{{ old('pickup_location') }}" placeholder="e.g. Jeddah King Khaled International Airport" required>
                        </div>
                    </div>
                </div>

                <!-- Dropoff Details -->
                <div class="form-section">
                    <h3><span>03</span> Dropoff details</h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="dropoff_date">Drop-off date <span class="required-star">*</span></label>
                            <input class="form-control" id="dropoff_date" name="dropoff_date" type="date" value="{{ old('dropoff_date') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="dropoff_time">Drop-off time <span class="required-star">*</span></label>
                            <input class="form-control" id="dropoff_time" name="dropoff_time" type="time" value="{{ old('dropoff_time', '12:00') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="dropoff_terminal">Drop-off terminal</label>
                            <input class="form-control" id="dropoff_terminal" name="dropoff_terminal" value="{{ old('dropoff_terminal') }}" placeholder="e.g. Terminal 2">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="dropoff_location">Drop-off location <span class="required-star">*</span></label>
                            <input class="form-control" id="dropoff_location" name="dropoff_location" value="{{ old('dropoff_location') }}" placeholder="e.g. Ritz-Carlton Hotel, Riyadh" required>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Details -->
                <div class="form-section">
                    <h3><span>04</span> Vehicle details</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="vehicle_type">Vehicle type <span class="required-star">*</span></label>
                            <select class="form-select" id="vehicle_type" name="vehicle_type" required>
                                <option value="">Select vehicle type</option>
                                @foreach (($categories->firstWhere('slug', 'vehicletype')?->values ?? collect()) as $value)
                                    <option value="{{ $value->value }}" {{ old('vehicle_type') === $value->value ? 'selected' : '' }}>{{ $value->value }}</option>
                                @endforeach
                                <option value="Other" {{ old('vehicle_type') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <input class="form-control other-field mt-2 d-none" id="vehicle_type_other" name="vehicle_type_other" placeholder="Specify vehicle type">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="vehicle_model">Vehicle model / similar</label>
                            <input class="form-control" id="vehicle_model" name="vehicle_model" value="{{ old('vehicle_model') }}" placeholder="e.g. Toyota Camry or similar">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="insurance_type">Insurance type</label>
                            <select class="form-select" id="insurance_type" name="insurance_type">
                                <option value="">Select insurance type</option>
                                <option value="CDW" {{ old('insurance_type') === 'CDW' ? 'selected' : '' }}>CDW</option>
                                <option value="LDW" {{ old('insurance_type') === 'LDW' ? 'selected' : '' }}>LDW</option>
                                <option value="SCD" {{ old('insurance_type') === 'SCD' ? 'selected' : '' }}>SCD</option>
                                <option value="Super CDW" {{ old('insurance_type') === 'Super CDW' ? 'selected' : '' }}>Super CDW</option>
                                <option value="Full Insurance" {{ old('insurance_type') === 'Full Insurance' ? 'selected' : '' }}>Full Insurance</option>
                                <option value="No Insurance" {{ old('insurance_type') === 'No Insurance' ? 'selected' : '' }}>No Insurance</option>
                                <option value="Other" {{ old('insurance_type') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <input class="form-control other-field mt-2 d-none" id="insurance_type_other" name="insurance_type_other" placeholder="Specify insurance type">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="rental_duration">Rental duration (days)</label>
                            <input class="form-control" id="rental_duration" name="rental_duration" type="number" min="1" max="365" value="{{ old('rental_duration') }}" placeholder="e.g. 4">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="branch_timing">Branch timing</label>
                            <input class="form-control" id="branch_timing" name="branch_timing" value="{{ old('branch_timing') }}" placeholder="e.g. 24 Hrs.">
                        </div>
                    </div>
                </div>

                <!-- Flight Number -->
                <div class="form-section">
                    <h3><span>05</span> Flight Number</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="flight_number">Flight number</label>
                            <input class="form-control" id="flight_number" name="flight_number" value="{{ old('flight_number') }}" placeholder="e.g. SV 1024">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="flight_airport">Arrival airport / terminal</label>
                            <input class="form-control" id="flight_airport" name="flight_airport" value="{{ old('flight_airport') }}" placeholder="e.g. Jeddah Airport (KAIA) Terminal 1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="flight_arrival_date">Arrival date</label>
                            <input class="form-control" id="flight_arrival_date" name="flight_arrival_date" type="date" value="{{ old('flight_arrival_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="flight_arrival_time">Arrival time</label>
                            <input class="form-control" id="flight_arrival_time" name="flight_arrival_time" type="time" value="{{ old('flight_arrival_time') }}">
                        </div>
                    </div>
                </div>

                <!-- Supplier Reference -->
                <div class="form-section">
                    <h3><span>06</span> Supplier reference</h3>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="supplier_reference">Supplier reference</label>
                            <input class="form-control" id="supplier_reference" name="supplier_reference" value="{{ old('supplier_reference') }}" placeholder="e.g. REF-2024-001">
                        </div>
                    </div>
                </div>

                <!-- Vehicle Photo -->
                <div class="form-section">
                    <h3><span>07</span> Vehicle photo</h3>
                    <div class="photo-control">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="include_car_photo" name="include_car_photo" value="1" {{ old('include_car_photo') ? 'checked' : '' }}>
                            <label class="form-check-label" for="include_car_photo">Include vehicle photo</label>
                        </div>
                        <label class="file-drop" for="car_photo">
                            <span class="file-drop-icon">+</span>
                            <strong>Choose a vehicle image</strong>
                            <small>PNG, JPG or WebP up to 5 MB</small>
                        </label>
                        <input class="d-none" id="car_photo" name="car_photo" type="file" accept="image/png,image/jpeg,image/webp">
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="form-section">
                    <h3><span>08</span> Important notes</h3>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="important_notes">Important notes</label>
                            <textarea class="form-control" id="important_notes" name="important_notes" rows="3" placeholder="e.g. Free cancellation up to 24 hours before pickup. CDW coverage included. Driver must be 25+ years old.">{{ old('important_notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="form-section">
                    <h3><span>09</span> Terms</h3>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="terms_text">Rental terms <small class="text-muted">(pre-filled — edit or remove items as needed; shown in the voucher side panel)</small></label>
                            <textarea class="form-control" id="terms_text" name="terms_text" rows="8">{{ old('terms_text', \App\Models\Voucher::defaultTerms('car-rental')) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Booking Consultant -->
                <div class="form-section">
                    <h3><span>10</span> Booking consultant</h3>
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
                        <select class="form-select" id="voucher_theme_select" name="voucher_theme_display" onchange="document.getElementById('voucher_theme').value = this.value; dispatchPreview();">
                            <option value="classic">Alma Executive Classic</option>
                            <option value="modern">Alma Modern Premium</option>
                        </select>
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
                    @include('vouchers.car-rental.partials.voucher-layout', [
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
    document.addEventListener('DOMContentLoaded', function () {
        try {
            console.log('DOMContentLoaded fired');
            const form = document.getElementById('voucher-form');
            const themeSelect = document.getElementById('voucher_theme_select');
            const themeInput = document.getElementById('voucher_theme');
            const voucherCard = document.getElementById('printVoucher');
            if (themeSelect && themeInput) { themeSelect.value = themeInput.value; }
            if (themeSelect && voucherCard) {
                themeSelect.addEventListener('change', function () {
                    themeInput.value = this.value;
                    voucherCard.classList.remove('theme-classic', 'theme-modern');
                    voucherCard.classList.add('theme-' + this.value);
                    dispatchPreview();
                });
            }
            const pickupDate = document.getElementById('pickup_date');
            const dropoffDate = document.getElementById('dropoff_date');
            const photoToggle = document.getElementById('include_car_photo');
            const photoInput = document.getElementById('car_photo');
            const employeeSelect = document.getElementById('employee_id');
            let carPhoto = '';
            const DEFAULT_TERMS = @json(\App\Models\Voucher::defaultTerms('car-rental'));

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
            };

            const dispatchPreview = () => {
                document.title = 'PREVIEW_RUNNING_' + Date.now();
                document.body.setAttribute('data-preview-status', 'running');
                console.log('dispatchPreview running');

                const setValue = (id, value, useHtml = false) => {
                    try {
                        const element = document.getElementById(id);
                        if (!element) { console.warn('[preview] missing element: ' + id); return; }
                        if (useHtml) {
                            element.innerHTML = value;
                        } else {
                            element.textContent = value;
                        }
                    } catch (err) {
                        console.error('[setValue]', id, err);
                    }
                };

                let days = 0;
                try {
                    const durInput = document.getElementById('rental_duration');
                    days = durInput && durInput.value ? parseInt(durInput.value, 10) : 0;
                } catch (err) {
                    console.error('[days]', err);
                }

                try { setValue('previewRentalDuration', days > 0 ? days + ' ' + (days === 1 ? 'Day' : 'Days') : 'e.g. 4 Days'); } catch (err) { console.error('[daysField]', err); }

                // Booking Details
                setValue('previewGuest', document.getElementById('guest_name')?.value || 'Guest name');
                setValue('previewBookingNumber', document.getElementById('booking_number')?.value || '—');
                setValue('previewContactNumber', document.getElementById('contact_number')?.value || '—');
                setValue('previewIdPassport', document.getElementById('id_passport_number')?.value || '—');
                setValue('previewReference', document.getElementById('confirmation_reference')?.value || '—');

                // Pickup / Dropoff
                setValue('previewCheckIn', formatDate(pickupDate?.value) + ' <span style="font-weight: normal; font-size: 9px; color: #666;">(From ' + (document.getElementById('pickup_time')?.value || '10:00') + ')</span>', true);
                setValue('previewCheckOut', formatDate(dropoffDate?.value) + ' <span style="font-weight: normal; font-size: 9px; color: #666;">(Until ' + (document.getElementById('dropoff_time')?.value || '12:00') + ')</span>', true);

                try {
                    const pickupLocation = document.getElementById('pickup_location')?.value || 'Pickup location';
                    const dropoffLocation = document.getElementById('dropoff_location')?.value || 'Drop-off location';
                    setValue('previewPickupLocation', pickupLocation);
                    setValue('previewPickupLocation2', pickupLocation);
                    setValue('previewDropoffLocation', dropoffLocation);
                    setValue('previewDropoffLocation2', dropoffLocation);
                } catch (err) { console.error('[locations]', err); }

                setValue('previewPickupTerminal', document.getElementById('pickup_terminal')?.value || '');
                setValue('previewDropoffTerminal', document.getElementById('dropoff_terminal')?.value || '');
                try {
                    const pickupTerminalCard = document.getElementById('previewPickupTerminalCard');
                    const dropoffTerminalCard = document.getElementById('previewDropoffTerminalCard');
                    if (pickupTerminalCard) { pickupTerminalCard.classList.toggle('d-none', !document.getElementById('pickup_terminal')?.value); }
                    if (dropoffTerminalCard) { dropoffTerminalCard.classList.toggle('d-none', !document.getElementById('dropoff_terminal')?.value); }
                } catch (err) { console.error('[terminals]', err); }

                // Vehicle Details
                setValue('previewVehicleType', getOptionValue('vehicle_type', 'vehicle_type_other'));
                setValue('previewHeroVehicleType', getOptionValue('vehicle_type', 'vehicle_type_other') || 'Vehicle type');
                setValue('previewVehicleModel', document.getElementById('vehicle_model')?.value || '');
                setValue('previewInsuranceType', getOptionValue('insurance_type', 'insurance_type_other'));
                setValue('previewBranchTiming', document.getElementById('branch_timing')?.value || '—');

                // Flight Number
                setValue('previewFlightNumber', document.getElementById('flight_number')?.value || '—');
                setValue('previewFlightAirport', document.getElementById('flight_airport')?.value || '—');
                setValue('previewFlightArrivalDate', formatDate(document.getElementById('flight_arrival_date')?.value));
                setValue('previewFlightArrivalTime', document.getElementById('flight_arrival_time')?.value || '—');

                setValue('previewSupplierReference', document.getElementById('supplier_reference')?.value || '—');
                setValue('previewImportantNotes', document.getElementById('important_notes')?.value || '');

                // Terms side list (live from textarea, defaults when empty)
                try {
                    const termsList = document.getElementById('previewTermsList');
                    const termsRaw = (document.getElementById('terms_text')?.value || '').trim() || DEFAULT_TERMS;
                    if (termsList) {
                        termsList.innerHTML = '';
                        termsRaw.split('\n').map(l => l.replace(/^\s*\d+\.\s*/, '').trim()).filter(Boolean).forEach(line => {
                            const li = document.createElement('li');
                            li.textContent = line;
                            termsList.appendChild(li);
                        });
                    }
                } catch (err) { console.error('[terms preview]', err); }

                // Car Photo
                try {
                    const carPhotoElement = document.getElementById('previewCarPhoto');
                    const photoPlaceholder = document.getElementById('previewPhotoPlaceholder');
                    const showCarPhoto = document.getElementById('include_car_photo')?.checked && !!carPhoto;
                    if (carPhotoElement) {
                        carPhotoElement.src = showCarPhoto ? carPhoto : carPhotoElement.dataset.heroImage;
                        carPhotoElement.classList.remove('d-none');
                    }
                    if (photoPlaceholder) photoPlaceholder.classList.add('d-none');
                } catch (err) { console.error('[carPhoto]', err); }

                // Consultant details
                try {
                    const option = employeeSelect.selectedOptions[0] || {};
                    const consultantContact = [option.dataset?.phone, option.dataset?.email].filter(Boolean).join(' · ');
                    setValue('previewConsultantName', option.dataset?.name || 'Alma Travel Team');
                    setValue('previewConsultantEmail', option.dataset?.email || 'Booking support');
                    setValue('previewConsultantPhone', option.dataset?.phone || ({!! json_encode($settings->phone ?? '+966 920022050') !!}));

                    window.dispatchEvent(new CustomEvent('voucherPreviewUpdate', {
                        detail: {
                            guestName: document.getElementById('guest_name')?.value || 'Guest name',
                            bookingNumber: document.getElementById('booking_number')?.value || '—',
                            confirmationReference: document.getElementById('confirmation_reference')?.value || '—',
                            pickupLocation: document.getElementById('pickup_location')?.value || 'Pickup location',
                            dropoffLocation: document.getElementById('dropoff_location')?.value || 'Drop-off location',
                            carPhoto: !!carPhoto,
                            consultantName: option.dataset?.name || 'Alma Travel Team',
                            consultantContact: consultantContact || 'Phone · Email',
                        },
                    }));
                } catch (err) { console.error('[voucherPreviewUpdate]', err); }
            };

            window.dispatchPreview = dispatchPreview;
            console.log('ATTACHING LISTENERS');
            form.addEventListener('input', () => {
                updateOtherField('vehicle_type', 'vehicle_type_other');
                updateOtherField('insurance_type', 'insurance_type_other');
                dispatchPreview();
            });
            form.addEventListener('change', dispatchPreview);
            pickupDate.addEventListener('change', dispatchPreview);
            dropoffDate.addEventListener('change', dispatchPreview);
            console.log('LISTENERS ATTACHED');
            window.addEventListener('voucherThemeChange', (event) => {
                document.getElementById('voucher_theme').value = event.detail.themeName;
            });
            photoInput.addEventListener('change', () => {
                const file = photoInput.files[0];
                if (!file) {
                    carPhoto = '';
                    dispatchPreview();
                    return;
                }
                const reader = new FileReader();
                reader.addEventListener('load', () => {
                    carPhoto = reader.result;
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
