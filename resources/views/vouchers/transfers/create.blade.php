@extends('layouts.app')
@section('title', 'Transfers Voucher Generator | Alma Travel')
@section('content')
<section class="generator-page">
    <div class="container-xl">
        <div class="page-intro row align-items-end mb-4">
            <div class="col-lg-8">
                <p class="eyebrow">Alma Travel / Consultant workspace</p>
                <h1>Transfers confirmation<br><em>voucher generator</em></h1>
                <p class="lead mb-0">Create a polished, brand-ready confirmation voucher with a live preview before you export or print.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <span class="status-pill"><i></i> Live preview ready</span>
                <p class="small text-muted mb-0">All fields update instantly.<br>Required fields are marked with <span class="required-star">*</span></p>
            </div>
        </div>

        <form id="voucher-form" class="generator-layout" method="POST" action="{{ route('transfers-voucher.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="voucher_theme" id="voucher_theme" value="{{ $settings->voucher_theme ?? 'classic' }}">
            <section class="form-panel" aria-label="Voucher details">
                <div class="panel-heading">
                    <div><p class="eyebrow mb-1">Step 01 / Booking details</p><h2>Enter the transfer information</h2></div>
                    <span class="panel-number">01</span>
                </div>

                <div class="form-section">
                    <h3><span>01</span> Trip &amp; booking</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="trip_type">Trip type <span class="required-star">*</span></label>
                            <select class="form-select" id="trip_type" name="trip_type" required>
                                <option value="one_way" {{ old('trip_type', 'one_way') === 'one_way' ? 'selected' : '' }}>One Way</option>
                                <option value="round_trip" {{ old('trip_type') === 'round_trip' ? 'selected' : '' }}>Round Trip</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="booking_date">Booking date</label>
                            <input class="form-control" id="booking_date" name="booking_date" type="date" value="{{ old('booking_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="guest_name">Guest name <span class="required-star">*</span></label>
                            <input class="form-control" id="guest_name" name="guest_name" value="{{ old('guest_name') }}" placeholder="e.g. Mr. Ahmed Alotaibi" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="confirmation_reference">Confirmation / PNR reference <span class="required-star">*</span></label>
                            <input class="form-control" id="confirmation_reference" name="confirmation_reference" value="{{ old('confirmation_reference') }}" placeholder="e.g. ALM8K2" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="guest_mobile">Guest mobile</label>
                            <input class="form-control" id="guest_mobile" name="guest_mobile" value="{{ old('guest_mobile') }}" placeholder="e.g. +966 50 123 4567">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><span>02</span> Flight Number</h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="flight_number">Flight number</label>
                            <input class="form-control" id="flight_number" name="flight_number" value="{{ old('flight_number') }}" placeholder="e.g. SV123, EK456">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="flight_date">Flight date</label>
                            <input class="form-control" id="flight_date" name="flight_date" type="date" value="{{ old('flight_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="flight_time">Flight time</label>
                            <input class="form-control" id="flight_time" name="flight_time" type="time" value="{{ old('flight_time') }}">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><span>03</span> Journey details</h3>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label" for="pickup_date">Pickup date <span class="required-star">*</span></label>
                            <input class="form-control" id="pickup_date" name="pickup_date" type="date" value="{{ old('pickup_date') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="pickup_time">Pickup time</label>
                            <input class="form-control" id="pickup_time" name="pickup_time" type="time" value="{{ old('pickup_time', '10:00') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="dropoff_date">Drop-off date</label>
                            <input class="form-control" id="dropoff_date" name="dropoff_date" type="date" value="{{ old('dropoff_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="dropoff_time">Drop-off time</label>
                            <input class="form-control" id="dropoff_time" name="dropoff_time" type="time" value="{{ old('dropoff_time', '12:00') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="pickup_location">Pickup location <span class="required-star">*</span></label>
                            <input class="form-control" id="pickup_location" name="pickup_location" value="{{ old('pickup_location') }}" placeholder="e.g. Jeddah King Abdulaziz International Airport" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="dropoff_location">Drop-off location <span class="required-star">*</span></label>
                            <input class="form-control" id="dropoff_location" name="dropoff_location" value="{{ old('dropoff_location') }}" placeholder="e.g. Ritz-Carlton Hotel, Riyadh" required>
                        </div>
                    </div>
                </div>

                <div class="form-section d-none" id="return_section">
                    <h3><span>04</span> Return journey <small class="text-muted">(round trip)</small></h3>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label" for="return_pickup_date">Return pickup date</label>
                            <input class="form-control" id="return_pickup_date" name="return_pickup_date" type="date" value="{{ old('return_pickup_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="return_pickup_time">Pickup time</label>
                            <input class="form-control" id="return_pickup_time" name="return_pickup_time" type="time" value="{{ old('return_pickup_time') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="return_dropoff_date">Return drop-off date</label>
                            <input class="form-control" id="return_dropoff_date" name="return_dropoff_date" type="date" value="{{ old('return_dropoff_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="return_dropoff_time">Drop-off time</label>
                            <input class="form-control" id="return_dropoff_time" name="return_dropoff_time" type="time" value="{{ old('return_dropoff_time') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="return_pickup_location">Return pickup location</label>
                            <input class="form-control" id="return_pickup_location" name="return_pickup_location" value="{{ old('return_pickup_location') }}" placeholder="e.g. Hotel lobby">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="return_dropoff_location">Return drop-off location</label>
                            <input class="form-control" id="return_dropoff_location" name="return_dropoff_location" value="{{ old('return_dropoff_location') }}" placeholder="e.g. Jeddah Airport">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="return_flight_number">Return flight number</label>
                            <input class="form-control" id="return_flight_number" name="return_flight_number" value="{{ old('return_flight_number') }}" placeholder="e.g. SV124">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="return_flight_date">Return flight date</label>
                            <input class="form-control" id="return_flight_date" name="return_flight_date" type="date" value="{{ old('return_flight_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="return_flight_time">Return flight time</label>
                            <input class="form-control" id="return_flight_time" name="return_flight_time" type="time" value="{{ old('return_flight_time') }}">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><span>05</span> Vehicle &amp; guests</h3>
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
                        <div class="col-md-3">
                            <label class="form-label" for="passengers">Number of Passengers <span class="required-star">*</span></label>
                            <input class="form-control" id="passengers" name="passengers" type="number" min="1" max="50" value="{{ old('passengers', '1') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="luggage_count">Luggage (pieces)</label>
                            <input class="form-control" id="luggage_count" name="luggage_count" type="number" min="0" max="50" value="{{ old('luggage_count', '0') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="driver_name">Driver name</label>
                            <input class="form-control" id="driver_name" name="driver_name" value="{{ old('driver_name') }}" placeholder="e.g. Mr. Jahangir">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="driver_number">Driver number</label>
                            <input class="form-control" id="driver_number" name="driver_number" value="{{ old('driver_number') }}" placeholder="e.g. 056 454 7615">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><span>06</span> Vehicle photo</h3>
                    <div class="photo-control">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="include_photo" name="include_photo" value="1" {{ old('include_photo') ? 'checked' : '' }}>
                            <label class="form-check-label" for="include_photo">Include vehicle photo</label>
                        </div>
                        <label class="file-drop" for="photo">
                            <span class="file-drop-icon">+</span>
                            <strong>Choose a vehicle image</strong>
                            <small>PNG, JPG or WebP up to 5 MB</small>
                        </label>
                        <input class="d-none" id="photo" name="photo" type="file" accept="image/png,image/jpeg,image/webp">
                    </div>
                </div>

                <div class="form-section">
                    <h3><span>07</span> Special requests</h3>
                    <label class="form-label" for="special_requests">Special requirements</label>
                    @php
                        $defaultTransferTerms = "1. Cancellation and extra charges policy applies.\n2. Smoking and food are strictly prohibited inside the vehicle — violations incur cleaning/penalty charges.\n3. Extra en-route stops not in the confirmed itinerary are charged separately.\n4. Waiting charges: 150 SAR per hour.\n5. 100% charge within 24 hours or no-show; cancellation after confirmation counts as no-show.\n6. Grace period: 30 min (domestic airport) / 15 min (other locations); extra waiting billed per 30 minutes.";
                    @endphp
                    <textarea class="form-control" id="special_requests" name="special_requests" rows="7" placeholder="e.g. Child seat, wheelchair access, smoking car">{{ old('special_requests', $defaultTransferTerms) }}</textarea>
                </div>

                <div class="form-section">
                    <h3><span>08</span> Booking consultant</h3>
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
                    @include('vouchers.transfers.partials.voucher-layout', [
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
        const photoToggle = document.getElementById('include_photo');
        const photoInput = document.getElementById('photo');
        const employeeSelect = document.getElementById('employee_id');
        const tripTypeSelect = document.getElementById('trip_type');
        const returnSection = document.getElementById('return_section');
        let vehiclePhoto = '';

        const formatDate = (value) => {
            if (!value) return 'Select date';
            const date = new Date(value + 'T00:00:00');
            return new Intl.DateTimeFormat('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }).format(date);
        };

        const formatTime = (value) => {
            if (!value) return '—';
            const parts = value.split(':');
            if (parts.length < 2) return value;
            let h = parseInt(parts[0], 10);
            const m = parts[1];
            const ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12 || 12;
            return h + ':' + m + ' ' + ampm;
        };

        const updateTripType = () => {
            const isRound = tripTypeSelect && tripTypeSelect.value === 'round_trip';
            if (returnSection) returnSection.classList.toggle('d-none', !isRound);
            const returnBlock = document.getElementById('previewReturnBlock');
            if (returnBlock) returnBlock.classList.toggle('d-none', !isRound);
            setTripLabel();
        };

        const setTripLabel = () => {
            const el = document.getElementById('previewTripType');
            if (el && tripTypeSelect) el.textContent = tripTypeSelect.value === 'round_trip' ? 'Round Trip' : 'One Way';
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

            setValue('previewGuest', document.getElementById('guest_name')?.value || 'Guest name');
            setValue('previewReference', document.getElementById('confirmation_reference')?.value || 'Reference');
            setTripLabel();
            setValue('previewBookingDate', formatDate(document.getElementById('booking_date')?.value));
            setValue('previewGuestMobile', document.getElementById('guest_mobile')?.value || '—');
            setValue('previewCheckIn', formatDate(pickupDate?.value) + ' · ' + formatTime(document.getElementById('pickup_time')?.value));
            const dropoffDateVal = dropoffDate?.value;
            setValue('previewCheckOut', dropoffDateVal ? formatDate(dropoffDateVal) + ' · ' + formatTime(document.getElementById('dropoff_time')?.value) : '—');
            setValue('previewVehicleType', getOptionValue('vehicle_type', 'vehicle_type_other'));
            setValue('previewSideVehicleType', getOptionValue('vehicle_type', 'vehicle_type_other') || 'Vehicle type');
            setValue('previewPassengers', document.getElementById('passengers')?.value || '1');
            setValue('previewLuggageCount', document.getElementById('luggage_count')?.value || '0');
            setValue('previewDriverName', document.getElementById('driver_name')?.value || '—');
            setValue('previewDriverNumber', document.getElementById('driver_number')?.value || '—');
            // Flight number (outbound) — composed nicely
            const flightBits = [document.getElementById('flight_number')?.value || '', formatDate(document.getElementById('flight_date')?.value), formatTime(document.getElementById('flight_time')?.value)].filter(b => b && b !== 'Select date' && b !== '—');
            setValue('previewFlightDetails', flightBits.length ? flightBits.join(' · ') : '—');
            // Return leg
            setValue('previewReturnPickup', formatDate(document.getElementById('return_pickup_date')?.value) + ' · ' + formatTime(document.getElementById('return_pickup_time')?.value));
            setValue('previewReturnDropoff', formatDate(document.getElementById('return_dropoff_date')?.value) + ' · ' + formatTime(document.getElementById('return_dropoff_time')?.value));
            setValue('previewReturnPickupLocation', document.getElementById('return_pickup_location')?.value || '—');
            setValue('previewReturnDropoffLocation', document.getElementById('return_dropoff_location')?.value || '—');
            const retFlightBits = [document.getElementById('return_flight_number')?.value || '', formatDate(document.getElementById('return_flight_date')?.value), formatTime(document.getElementById('return_flight_time')?.value)].filter(b => b && b !== 'Select date' && b !== '—');
            setValue('previewReturnFlightDetails', retFlightBits.length ? retFlightBits.join(' · ') : '—');
            setValue('previewSpecialRequests', document.getElementById('special_requests')?.value || 'Special requests');
            const option = employeeSelect.selectedOptions[0] || {};
            setValue('previewConsultantName', option.dataset?.name || 'Alma Travel Team');
            setValue('previewConsultantEmail', option.dataset?.email || 'Booking support');
            setValue('previewConsultantPhone', option.dataset?.phone || ({!! json_encode($settings->phone ?? '+966 920022050') !!}));
            const pickupLocation = document.getElementById('pickup_location')?.value || 'Pickup location';
            const dropoffLocation = document.getElementById('dropoff_location')?.value || 'Drop-off location';
            setValue('previewPickupLocation', pickupLocation);
            setValue('previewDropoffLocation', dropoffLocation);

            try {
                const vehiclePhotoElement = document.getElementById('previewCarPhoto');
                const photoPlaceholder = document.getElementById('previewPhotoPlaceholder');
                const showPhoto = document.getElementById('include_photo')?.checked && !!vehiclePhoto;
                if (vehiclePhotoElement) {
                    vehiclePhotoElement.src = showPhoto ? vehiclePhoto : vehiclePhotoElement.dataset.heroImage;
                    vehiclePhotoElement.classList.remove('d-none');
                }
                if (photoPlaceholder) photoPlaceholder.classList.add('d-none');
            } catch (err) { console.error('[vehiclePhoto]', err); }
        };

        window.dispatchPreview = dispatchPreview;
        console.log('ATTACHING LISTENERS');
        form.addEventListener('input', () => {
            updateOtherField('vehicle_type', 'vehicle_type_other');
            dispatchPreview();
        });
        form.addEventListener('change', () => { updateTripType(); dispatchPreview(); });
        pickupDate.addEventListener('change', dispatchPreview);
        dropoffDate.addEventListener('change', dispatchPreview);
        if (tripTypeSelect) tripTypeSelect.addEventListener('change', () => { updateTripType(); dispatchPreview(); });
        updateTripType();
        console.log('LISTENERS ATTACHED');
        window.addEventListener('voucherThemeChange', (event) => {
            document.getElementById('voucher_theme').value = event.detail.themeName;
        });
        photoInput.addEventListener('change', () => {
            const file = photoInput.files[0];
            if (!file) {
                vehiclePhoto = '';
                dispatchPreview();
                return;
            }
            const reader = new FileReader();
            reader.addEventListener('load', () => {
                vehiclePhoto = reader.result;
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
