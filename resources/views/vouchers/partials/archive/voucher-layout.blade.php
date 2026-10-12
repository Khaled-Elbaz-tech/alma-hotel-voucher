@props(['settings', 'voucher' => null, 'preview' => false, 'logoData' => null, 'hotelPhotoData' => null])

@php
    $themeDefinitions = [
        'alma_classic' => [
            'primary' => '#063B3E',
            'accent' => '#C89B3C',
            'light' => '#FFFFFF',
            'dark' => '#063B3E',
            'font' => "'Playfair Display', Georgia, serif",
        ],
        'alma_modern' => [
            'primary' => '#063B3E',
            'accent' => '#C89B3C',
            'light' => '#FFFFFF',
            'dark' => '#063B3E',
            'font' => "'Playfair Display', Georgia, serif",
        ],
        'alma_premium' => [
            'primary' => '#063B3E',
            'accent' => '#C89B3C',
            'light' => '#FFFFFF',
            'dark' => '#063B3E',
            'font' => "'Playfair Display', Georgia, serif",
        ],
    ];

    $themeName = ($voucher?->voucher_theme ?? null) ?: ($settings->voucher_theme ?? 'alma_classic');
    $themeDefinition = $themeDefinitions[$themeName] ?? $themeDefinitions['alma_classic'];
    $themeFont = $themeDefinition['font'];
    $preview = (bool) $preview;

    $value = fn (string $key, string $fallback = '') => $preview ? $fallback : data_get($voucher, $key, $fallback);
    $formattedCheckIn = $preview ? 'Select date' : $voucher->check_in_date->format('d M Y');
    $formattedCheckOut = $preview ? 'Select date' : $voucher->check_out_date->format('d M Y');
    $nights = $preview ? 0 : $voucher->nights_count;
    $hotelName = $preview ? 'Hotel name' : $voucher->hotel_name;
    $hotelAddress = $preview ? 'Hotel address' : $voucher->hotel_address;
    $hotelPhone = $preview ? 'Hotel phone' : ($voucher->hotel_phone ?: '—');
    $supplierReference = $preview ? 'Supplier reference' : ($voucher->supplier_reference ?: $voucher->confirmation_reference);
    $occupancy = $preview ? '1 Room | 2 Adults' : ($voucher->occupancy ?: '1 Room | 2 Adults');
    $specialRequests = $preview ? 'Special requests' : ($voucher->special_requests ?: '—');
    $policy = $preview ? 'Select a policy' : $voucher->cancellation_policy;
    $employee = $preview ? null : $voucher->employee;
    $logoSource = $logoData ?: $settings->logoUrl();
    $hasHotelPhoto = ! $preview && $voucher->include_hotel_photo && ($voucher->hotel_photo_path || $hotelPhotoData);
    $photoDisabled = ! $hasHotelPhoto;
    $voucherNumber = $preview ? 'ALMA-HV-20260917-0001' : $voucher->voucher_number;
    $voucherDate = $preview ? now()->format('d M Y') : $voucher->created_at->format('d M Y');
@endphp

<div class="voucher executive-premium-voucher {{ $preview ? 'voucher-preview' : 'voucher-final' }} {{ $photoDisabled ? 'voucher-photo-disabled' : '' }}" id="voucherPreview" data-voucher-theme data-theme-name="{{ $themeName }}" data-theme-primary="{{ $themeDefinition['primary'] }}" data-theme-accent="{{ $themeDefinition['accent'] }}" data-theme-surface-light="{{ $themeDefinition['light'] }}" data-theme-surface-dark="{{ $themeDefinition['dark'] }}" data-theme-font="{{ $themeFont }}" style="--brand-primary: {{ $themeDefinition['primary'] }}; --brand-accent: {{ $themeDefinition['accent'] }}; --brand-surface-light: {{ $themeDefinition['light'] }}; --brand-surface-dark: {{ $themeDefinition['dark'] }}; --text-on-dark: #FFFFFF; --text-on-primary: #FFFFFF; --text-on-light: #1A202C; --voucher-font: {{ $themeFont }};">
    <div class="executive-voucher-frame">
        <header class="executive-header">
            <div class="executive-header-inner">
                <div class="executive-brand">
                    <div class="executive-logo">
                        @if ($logoSource)
                            <img class="executive-logo-image" src="{{ $logoSource }}" alt="{{ $settings->company_name }} logo">
                        @else
                            <span class="executive-logo-mark">AT</span>
                        @endif
                    </div>
                    <div class="executive-brand-copy">
                        <strong>{{ $settings->company_name }}</strong>
                        <span>{{ $settings->tagline }}</span>
                    </div>
                </div>

                <div class="executive-title">
                    <span class="executive-title-kicker">PRIVATE HOTEL CONFIRMATION</span>
                    <h1>CONFIRMATION VOUCHER</h1>
                    <span class="executive-title-divider"></span>
                    <p>YOUR JOURNEY, OUR COMMITMENT</p>
                </div>

                <div class="executive-voucher-meta">
                    <span>VOUCHER</span>
                    <strong id="previewVoucherNumber">{{ $voucherNumber }}</strong>
                    <span id="previewVoucherDate">{{ $voucherDate }}</span>
                </div>
            </div>
        </header>

        <section class="executive-body">
            <div class="executive-details">
                <div class="executive-section-heading">
                    <span>BOOKING INFORMATION</span>
                    <strong>Guest stay details</strong>
                </div>

                <div class="executive-cards">
                    <div class="executive-card executive-card-wide">
                        <span class="executive-card-icon">G</span>
                        <div class="executive-card-copy">
                            <span class="executive-card-label">Guest Name</span>
                            <strong class="executive-card-value" id="previewGuest">{{ $value('guest_name', 'Guest name') }}</strong>
                        </div>
                    </div>

                    <div class="executive-card">
                        <span class="executive-card-icon">C</span>
                        <div class="executive-card-copy">
                            <span class="executive-card-label">Confirmation Number</span>
                            <strong class="executive-card-value" id="previewReference">{{ $value('confirmation_reference', 'Reference') }}</strong>
                        </div>
                    </div>

                    <div class="executive-card">
                        <span class="executive-card-icon">S</span>
                        <div class="executive-card-copy">
                            <span class="executive-card-label">Supplier Reference</span>
                            <strong class="executive-card-value" id="previewSupplier">{{ $supplierReference }}</strong>
                        </div>
                    </div>

                    <div class="executive-card">
                        <span class="executive-card-icon">I</span>
                        <div class="executive-card-copy">
                            <span class="executive-card-label">Check-In</span>
                            <strong class="executive-card-value" id="previewCheckIn">{{ $formattedCheckIn }}</strong>
                        </div>
                    </div>

                    <div class="executive-card">
                        <span class="executive-card-icon">O</span>
                        <div class="executive-card-copy">
                            <span class="executive-card-label">Check-Out</span>
                            <strong class="executive-card-value" id="previewCheckOut">{{ $formattedCheckOut }}</strong>
                        </div>
                    </div>

                    <div class="executive-card">
                        <span class="executive-card-icon">N</span>
                        <div class="executive-card-copy">
                            <span class="executive-card-label">Nights</span>
                            <strong class="executive-card-value"><span id="previewNightsPreview">{{ $nights }}</span> <span id="previewNightsLabel">nights</span></strong>
                        </div>
                    </div>

                    <div class="executive-card">
                        <span class="executive-card-icon">P</span>
                        <div class="executive-card-copy">
                            <span class="executive-card-label">Occupancy</span>
                            <strong class="executive-card-value" id="previewOccupancy">{{ $occupancy }}</strong>
                        </div>
                    </div>

                    <div class="executive-card">
                        <span class="executive-card-icon">M</span>
                        <div class="executive-card-copy">
                            <span class="executive-card-label">Meal Plan</span>
                            <strong class="executive-card-value" id="previewMeal">{{ $value('meal_plan', 'Select option') }}</strong>
                        </div>
                    </div>

                    <div class="executive-card">
                        <span class="executive-card-icon">R</span>
                        <div class="executive-card-copy">
                            <span class="executive-card-label">Room Type</span>
                            <strong class="executive-card-value" id="previewRoom">{{ $value('room_type', 'Select option') }}</strong>
                        </div>
                    </div>

                    <div class="executive-card">
                        <span class="executive-card-icon">A</span>
                        <div class="executive-card-copy">
                            <span class="executive-card-label">Payment Method</span>
                            <strong class="executive-card-value" id="previewPayment">{{ $value('payment_method', 'Select option') }}</strong>
                        </div>
                    </div>

                    <div class="executive-card executive-card-wide executive-special-card">
                        <span class="executive-card-icon">Q</span>
                        <div class="executive-card-copy">
                            <span class="executive-card-label">Special Requests</span>
                            <strong class="executive-card-value executive-card-value-small" id="previewRequests">{{ $specialRequests }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="executive-hotel">
                <div class="executive-hotel-copy">
                    <span class="executive-hotel-kicker">HOTEL SHOWCASE</span>
                    <h2 id="previewHotelTitle">{{ $hotelName }}</h2>
                    <div class="executive-hotel-meta">
                        <span id="previewHotelAddress">{{ $hotelAddress }}</span>
                        <span id="previewHotelPhone">{{ $hotelPhone }}</span>
                    </div>
                </div>

                <div class="executive-hotel-image" id="previewHotelImageWrap">
                    @if ($preview)
                        <img id="previewHotelPhoto" class="executive-hotel-photo" src="" alt="Hotel preview">
                        <div id="previewPhotoPlaceholder" class="executive-hotel-photo-placeholder">Hotel image not included</div>
                    @elseif ($hasHotelPhoto)
                        <img class="executive-hotel-photo" src="{{ $hotelPhotoData ?: $voucher->hotelPhotoUrl() }}" alt="{{ $voucher->hotel_name }}">
                    @else
                        <div class="executive-hotel-photo-placeholder">Hotel image not included</div>
                    @endif
                </div>

                <div class="executive-policy-card">
                    <div class="executive-policy-heading">
                        <span class="executive-policy-icon">i</span>
                        <span>CANCELLATION POLICY</span>
                    </div>
                    <p id="previewPolicy">{{ $policy }}</p>
                </div>
            </aside>
        </section>

        <footer class="executive-footer">
            <div class="executive-footer-column">
                <span class="executive-footer-heading">ALMA TRAVEL</span>
                <strong>{{ $settings->address }}</strong>
                <span>+966 59 510 2152</span>
                <span>reservations@almatravel.co</span>
            </div>
            <div class="executive-footer-column">
                <span class="executive-footer-heading">BOOKING CONSULTANT</span>
                @if ($preview)
                    <strong id="previewConsultantName">Alma Travel Team</strong>
                    <span id="previewConsultantContact">Booking support</span>
                @elseif ($employee)
                    <strong id="previewConsultantName">{{ $employee->name }}</strong>
                    <span id="previewConsultantContact">{{ $employee->phone }} · {{ $employee->email }}</span>
                @else
                    <strong id="previewConsultantName">Alma Travel Team</strong>
                    <span id="previewConsultantContact">Booking support</span>
                @endif
            </div>
            <div class="executive-footer-column executive-footer-signature">
                <span class="executive-footer-heading">WITH COMPLIMENTS</span>
                <strong>Alma Travel</strong>
                <span>Concierge &amp; Travel Management</span>
            </div>
        </footer>
    </div>
</div>
