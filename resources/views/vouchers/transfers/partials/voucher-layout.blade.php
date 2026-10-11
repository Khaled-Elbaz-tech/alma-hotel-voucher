@php
    $preview = (bool) ($preview ?? false);
    $logoData = $logoData ?? null;
    $hotelPhotoData = $hotelPhotoData ?? null;

    $value = fn (string $key, string $fallback = '') => $preview ? $fallback : data_get($voucher, $key, $fallback);
    $formattedCheckIn = $preview ? 'Select date' : $voucher->check_in_date->format('d M Y');
    $formattedCheckOut = $preview ? 'Select date' : $voucher->check_out_date->format('d M Y');
    $pickupTime = $preview ? '10:00' : ($voucher->check_in_time ?? '10:00');
    $dropoffTime = $preview ? '12:00' : ($voucher->check_out_time ?? '12:00');
    $days = $preview ? 0 : $voucher->nights_count;
    $companyName = $preview ? 'Provider name' : ($voucher->company_name ?? '—');
    $companyAddress = $preview ? 'Provider address' : ($voucher->company_address ?? '—');
    $companyPhone = $preview ? 'Provider phone' : ($voucher->company_phone ?? '—');
    $companyEmail = $preview ? 'email@provider.com' : ($voucher->company_email ?? '—');
    $pickupLocation = $preview ? 'Pickup location' : ($voucher->pickup_location ?? '—');
    $dropoffLocation = $preview ? 'Drop-off location' : ($voucher->dropoff_location ?? '—');
    $vehicleType = $preview ? 'Select vehicle type' : ($voucher->vehicle_type ?? '—');
    $passengers = $preview ? '1' : ($voucher->passengers ?? '—');
    $luggageCount = $preview ? '0' : ($voucher->luggage_count ?? 0);
    $meetGreet = $preview ? false : (bool) $voucher->meet_greet;
    $flightNumber = $preview ? '—' : ($voucher->flight_number ?: '—');
    $specialRequests = $preview ? 'Special requests' : ($voucher->special_requests ?: '—');
    $insuranceType = $preview ? 'Select coverage' : ($voucher->insurance_type ?? '—');
    $employee = $preview ? null : $voucher->employee;
    $logoSource = $logoData ?? ($settings->logoUrl() ?? null);
    $hasPhoto = ! $preview && $voucher->include_hotel_photo && ($voucher->hotel_photo_path || $hotelPhotoData);
    $photoDisabled = ! $hasPhoto;
    $voucherNumber = $preview ? 'ALM-TR-20260917-0001' : $voucher->voucher_number;
    $voucherDate = $preview ? now()->format('d M Y') : $voucher->created_at->format('d M Y');
    $carPhotoUrl = $hotelPhotoData ?? ($voucher ? ($voucher->hotelPhotoUrl() ?? 'https://images.unsplash.com/photo-1580376249366-0705b5e7c4a0?auto=format&fit=crop&w=1000&q=80') : 'https://images.unsplash.com/photo-1580376249366-0705b5e7c4a0?auto=format&fit=crop&w=1000&q=80');
    $heroImage = asset('images/vouchers/transfers-hero.jpg');
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Playfair+Display:ital,wght@0,500;0,600;1,500&family=Inter:wght@400;500;600;700&display=swap');

    @page {
        size: A4 landscape;
        margin: 0;
    }

    /* ============ DESIGN TOKENS ============ */
    .voucher-card {
        --vp: #0B3B3E;            /* primary */
        --vpd: #062A2C;           /* primary deep */
        --va: #C8A24B;            /* gold accent */
        --va-soft: rgba(200, 162, 75, 0.13);
        --va-line: rgba(200, 162, 75, 0.38);
        --vline: #E8DECB;         /* hairline */
        --vpanel: #FBF9F4;        /* panel bg */
        --vpaper: #FFFFFF;
        --vink: #211A13;          /* body ink */
        --vmut: #6F6357;          /* muted */
        --vgrad: linear-gradient(118deg, #062A2C 0%, #0B3B3E 55%, #0E4A4D 100%);
        --vhead-bg: var(--vgrad);   /* header background */
        --vbrand: #ffffff;             /* logo text color */
    }
    .voucher-card.theme-modern {
        --vp: #A92D26;
        --vpd: #7A1F1A;
        --va: #D9A441;
        --va-soft: rgba(217, 164, 65, 0.13);
        --va-line: rgba(217, 164, 65, 0.40);
        --vgrad: linear-gradient(118deg, #7A1F1A 0%, #A92D26 55%, #C0453A 100%);
        --vhead-bg: linear-gradient(118deg, #F7F2E7 0%, #FFFFFF 55%, #EFE6D2 100%);
        --vbrand: #161310;
    }
    .voucher-card.theme-premium {
        --vp: #8B251E;
        --vpd: #5F1812;
        --va: #D4AF37;
        --va-soft: rgba(212, 175, 55, 0.13);
        --va-line: rgba(212, 175, 55, 0.42);
        --vgrad: linear-gradient(118deg, #5F1812 0%, #8B251E 55%, #B5322B 100%);
        --vbrand: #ffffff;
    }

    /* ============ CARD SHELL ============ */
    .voucher-card {
        width: 297mm;
        min-height: 200mm;
        margin: 0 auto;
        background: var(--vpaper);
        color: var(--vink);
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
        font-family: 'Inter', 'Georgia', serif;
        box-sizing: border-box;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .voucher-card *, .voucher-card *::before, .voucher-card *::after { box-sizing: border-box; }

    /* ============ HEADER ============ */
    .v-head {
        position: relative;
        display: grid;
        grid-template-columns: 1fr 1.55fr 1fr;
        align-items: center;
        gap: 12px;
        min-height: 34mm;
        padding: 10px 26px;
        background: var(--vhead-bg);
        border-bottom: 3px solid var(--va);
        color: #fff;
        overflow: hidden;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .v-head::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 8% 24%, rgba(255,255,255,0.10) 0 1.5px, transparent 2.5px),
            radial-gradient(circle at 18% 74%, rgba(255,255,255,0.07) 0 1px, transparent 2px),
            radial-gradient(circle at 93% 20%, rgba(255,255,255,0.08) 0 2px, transparent 3px),
            radial-gradient(circle at 78% 84%, var(--va-soft) 0 1.5px, transparent 2.5px),
            linear-gradient(115deg, transparent 0 70%, rgba(255,255,255,0.06) 70% 70.6%, transparent 70.6%);
    }
    .v-head::after {
        content: '';
        position: absolute;
        top: -78px;
        right: -56px;
        width: 150px;
        height: 150px;
        border: 1px solid var(--va-line);
        border-radius: 50%;
        box-shadow: 0 0 0 20px rgba(255,255,255,0.035), 0 0 0 38px var(--va-soft);
    }
    .v-head > * { position: relative; z-index: 1; }

    .v-brand { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .v-brand img { max-height: 40px; max-width: 150px; object-fit: contain; }
    .v-brand-name {
        font-family: 'Playfair Display', 'Georgia', serif;
        font-size: 25px;
        font-weight: 600;
        letter-spacing: 0.5px;
        color: var(--vbrand);
        line-height: 1.05;
        white-space: nowrap;
    }
    .v-brand-tag {
        font-family: 'Cinzel', 'Georgia', serif;
        font-size: 8.5px;
        font-weight: 600;
        letter-spacing: 2.6px;
        text-transform: uppercase;
        color: var(--va);
        margin-top: 5px;
    }

    .v-title { text-align: center; }
    .v-badge {
        width: 44px;
        height: 44px;
        margin: 0 auto 7px;
        border: 1.5px solid var(--va);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.06);
    }
    .v-badge svg { width: 22px; height: 22px; fill: var(--va); }
    .v-title h2 {
        font-family: 'Cinzel', 'Georgia', serif;
        font-size: 15.5px;
        font-weight: 700;
        letter-spacing: 3.2px;
        color: var(--va);
        margin: 0;
        text-transform: uppercase;
    }
    .v-title p {
        font-family: 'Playfair Display', 'Georgia', serif;
        font-style: italic;
        font-size: 12.5px;
        color: rgba(255,255,255,0.92);
        margin: 4px 0 0;
    }

    .v-meta { text-align: right; }
    .v-meta .lbl {
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        color: var(--va);
    }
    .v-meta .num {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.6px;
        margin: 2px 0 6px;
    }
    .v-meta .dt { font-size: 8.5px; letter-spacing: 1px; color: rgba(255,255,255,0.82); text-transform: uppercase; }
    .v-barcode {
        margin-top: 7px;
        margin-left: auto;
        width: 118px;
        height: 16px;
        background: repeating-linear-gradient(90deg, #fff 0 2px, transparent 2px 4px, #fff 4px 5px, transparent 5px 9px, #fff 9px 12px, transparent 12px 14px);
        opacity: 0.9;
        border-radius: 1px;
    }


    /* ============ MODERN PREMIUM: light header, dark ink ============ */
    .voucher-card.theme-modern .v-head { color: #1d1a14; }
    .voucher-card.theme-modern .v-head::before {
        background:
            radial-gradient(circle at 8% 24%, rgba(29,26,20,0.08) 0 1.5px, transparent 2.5px),
            radial-gradient(circle at 18% 74%, rgba(29,26,20,0.06) 0 1px, transparent 2px),
            radial-gradient(circle at 93% 20%, rgba(29,26,20,0.07) 0 2px, transparent 3px),
            radial-gradient(circle at 78% 84%, var(--va-soft) 0 1.5px, transparent 2.5px),
            linear-gradient(115deg, transparent 0 70%, rgba(29,26,20,0.05) 70% 70.6%, transparent 70.6%);
    }
    .voucher-card.theme-modern .v-badge { background: rgba(29,26,20,0.05); }
    .voucher-card.theme-modern .v-title h2 { color: #7A1F1A; }
    .voucher-card.theme-modern .v-title p { color: rgba(29,26,20,0.72); }
    .voucher-card.theme-modern .v-meta .lbl { color: #8a6d1f; }
    .voucher-card.theme-modern .v-meta .dt { color: rgba(29,26,20,0.66); }
    .voucher-card.theme-modern .v-brand-tag { color: #8a6d1f; }
    .voucher-card.theme-modern .v-barcode {
        background: repeating-linear-gradient(90deg, #1d1a14 0 2px, transparent 2px 4px, #1d1a14 4px 5px, transparent 5px 9px, #1d1a14 9px 12px, transparent 12px 14px);
    }

    /* ============ BODY ============ */
    .v-body {
        display: flex;
        gap: 16px;
        padding: 14px 22px 16px;
        flex: 1 1 auto;
        align-items: stretch;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .v-main { flex: 1.32; min-width: 0; display: flex; flex-direction: column; gap: 12px; }

    .v-sec-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'Cinzel', 'Georgia', serif;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 2.2px;
        text-transform: uppercase;
        color: var(--vp);
        margin: 0 0 8px;
    }
    .v-sec-title::after { content: ''; flex: 1; height: 1px; background: var(--va-line); }

    .v-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .v-field {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        min-width: 0;
        background: var(--vpanel);
        border: 1px solid var(--vline);
        border-radius: 8px;
        padding: 8px 10px;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .v-field.span2 { grid-column: span 2; }
    .v-ico {
        width: 30px;
        height: 30px;
        flex: 0 0 30px;
        border-radius: 8px;
        background: var(--va-soft);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .v-ico svg { width: 17px; height: 17px; fill: var(--va); }
    .v-tx { min-width: 0; flex: 1; }
    .v-label {
        display: block;
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 1.1px;
        text-transform: uppercase;
        color: var(--vmut);
        margin-bottom: 2px;
    }
    .v-val {
        display: block;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.4;
        color: var(--vink);
        overflow-wrap: break-word;
        word-break: break-word;
    }
    .v-val .v-sub { font-size: 9.5px; font-weight: 400; color: var(--vmut); }
    .v-val.long { font-size: 11px; font-weight: 500; }

    .v-note {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-top: auto;
        background: var(--va-soft);
        border: 1px dashed var(--va-line);
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 10px;
        font-weight: 600;
        color: var(--vp);
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .v-note svg { width: 16px; height: 16px; fill: var(--va); flex: 0 0 16px; }

    /* ============ SIDE / SHOWCASE ============ */
    .v-side { flex: 0.98; min-width: 0; display: flex; flex-direction: column; gap: 12px; }

    .v-show {
        border: 1px solid var(--vline);
        border-radius: 10px;
        overflow: hidden;
        background: var(--vpaper);
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .v-photo { position: relative; height: 148px; background: #e9e4d8; overflow: hidden; }
    .v-photo img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .v-photo::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 55%, rgba(6,42,44,0.28) 100%);
    }
    .v-photo-empty {
        height: 148px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: var(--vgrad);
        color: rgba(255,255,255,0.85);
    }
    .v-photo-empty svg { width: 34px; height: 34px; fill: var(--va); opacity: 0.9; }
    .v-photo-empty span { font-size: 8.5px; font-weight: 700; letter-spacing: 1.8px; text-transform: uppercase; }

    .v-show-body { padding: 12px 14px 13px; }
    .v-hero {
        font-family: 'Playfair Display', 'Georgia', serif;
        font-size: 19px;
        font-weight: 600;
        color: var(--vp);
        line-height: 1.2;
        margin: 0;
        overflow-wrap: break-word;
    }
    .v-hero-sub {
        font-size: 8.5px;
        font-weight: 700;
        letter-spacing: 1.6px;
        text-transform: uppercase;
        color: var(--va);
        margin-top: 3px;
    }
    .v-rows { margin-top: 9px; display: flex; flex-direction: column; gap: 5px; }
    .v-row { display: flex; align-items: flex-start; gap: 8px; font-size: 10px; color: var(--vmut); line-height: 1.45; }
    .v-row svg { width: 13px; height: 13px; fill: var(--va); flex: 0 0 13px; margin-top: 1px; }
    .v-row span { overflow-wrap: break-word; }

    .v-info {
        background: var(--vpanel);
        border: 1px solid var(--vline);
        border-radius: 10px;
        padding: 11px 13px;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .v-info-head { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
    .v-info-head svg { width: 15px; height: 15px; fill: var(--va); }
    .v-info-head h4 {
        font-family: 'Cinzel', 'Georgia', serif;
        font-size: 9.5px;
        font-weight: 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        color: var(--vp);
        margin: 0;
    }
    .v-info p { font-size: 10px; line-height: 1.55; color: var(--vmut); margin: 0; overflow-wrap: break-word; }

    /* ============ FOOTER ============ */
    .v-foot {
        position: relative;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        align-items: center;
        gap: 12px;
        margin-top: auto;
        padding: 11px 26px;
        background: var(--vgrad);
        border-top: 3px solid var(--va);
        color: #fff;
        overflow: hidden;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .v-foot::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 10% 72%, rgba(255,255,255,0.07) 0 1.5px, transparent 2.5px),
            radial-gradient(circle at 90% 26%, var(--va-soft) 0 2px, transparent 3px),
            linear-gradient(115deg, transparent 0 74%, rgba(255,255,255,0.05) 74% 74.5%, transparent 74.5%);
    }
    .v-foot > * { position: relative; z-index: 1; }
    .v-fcol h5 {
        font-size: 8.5px;
        font-weight: 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        color: var(--va);
        margin: 0 0 7px;
    }
    .v-fcol.mid { border-left: 1px solid var(--va-line); border-right: 1px solid var(--va-line); padding: 0 20px; }
    .v-fline { display: flex; align-items: center; gap: 8px; font-size: 10.5px; color: rgba(255,255,255,0.92); line-height: 1.7; }
    .v-fline svg { width: 12px; height: 12px; fill: var(--va); flex: 0 0 12px; }
    .v-fline span { overflow-wrap: break-word; }
    .v-comp { text-align: right; }
    .v-comp .t {
        font-family: 'Playfair Display', 'Georgia', serif;
        font-style: italic;
        font-size: 19px;
        color: var(--va);
    }
    .v-comp .s { font-size: 10px; font-weight: 600; letter-spacing: 1px; color: rgba(255,255,255,0.9); margin-top: 3px; }

    /* ============ PRINT ============ */
    @media print {
        html, body { margin: 0; padding: 0; }
        body * { visibility: hidden; }
        #printVoucher, #printVoucher * { visibility: visible; }
        #printVoucher {
            position: absolute;
            left: 0;
            top: 0;
            width: 297mm;
            min-height: 200mm;
            margin: 0;
            page-break-inside: avoid;
        }
    }
    .d-none { display: none !important; }

    /* ============ ROUTE TIMELINE (transfers) ============ */
    .v-route { margin-top: 12px; display: flex; flex-direction: column; }
    .v-route-pt { display: flex; gap: 10px; align-items: flex-start; }
    .v-route-dot {
        width: 12px; height: 12px; border-radius: 50%;
        background: var(--va); flex: 0 0 12px; margin-top: 2px;
        box-shadow: 0 0 0 4px var(--va-soft);
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .v-route-dot.end { background: var(--vp); box-shadow: 0 0 0 4px var(--va-soft); }
    .v-route-lbl { font-size: 8px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: var(--vmut); }
    .v-route-val { font-size: 12.5px; font-weight: 600; color: var(--vink); margin-top: 1px; overflow-wrap: break-word; }
    .v-route-line { display: flex; align-items: center; gap: 8px; margin: 2px 0 2px 5px; }
    .v-route-line::before { content: ''; width: 2px; height: 22px; background: var(--va-line); margin-left: 0; }
    .v-route-line svg { width: 16px; height: 16px; fill: var(--va); }
    .v-photo-sm, .v-photo-empty.v-photo-sm { height: 108px; }


    .v-photo-tag {
        position: absolute; left: 10px; bottom: 10px; z-index: 2;
        background: rgba(6,42,44,0.78); color: #fff;
        font-size: 8px; font-weight: 700; letter-spacing: 1.4px; text-transform: uppercase;
        padding: 5px 10px; border-radius: 20px;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }

</style>

<div class="voucher-card theme-{{ $preview ? 'classic' : (($theme = $voucher?->voucher_theme ?? $settings?->voucher_theme ?? 'classic') === 'alma_premium' ? 'premium' : $theme) }} {{ $preview ? 'voucher-preview' : 'voucher-final' }} {{ $photoDisabled ? 'voucher-photo-disabled' : '' }}" id="printVoucher">

    <!-- HEADER -->
    <div class="v-head">
        <div class="v-brand">
            @if($logoSource)
                <img src="{{ $logoSource }}" alt="{{ $settings->company_name }} logo">
            @endif
            <div>
                <div class="v-brand-name">{{ $settings->company_name }}</div>
                @if($settings->tagline)<div class="v-brand-tag">{{ $settings->tagline }}</div>@endif
            </div>
        </div>
        <div class="v-title">
            <div class="v-badge"><svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg></div>
            <h2>Transfers Voucher</h2>
            <p>Your Journey, Our Commitment</p>
        </div>
        <div class="v-meta">
            <div class="lbl">Voucher No.</div>
            <div class="num">{{ $voucherNumber }}</div>
            <div class="dt">Issued · {{ $voucherDate }}</div>
            <div class="v-barcode"></div>
        </div>
    </div>

    <!-- BODY -->
    <div class="v-body">

        <!-- MAIN -->
        <div class="v-main">
<section>
                <h3 class="v-sec-title">Booking Details</h3>
                <div class="v-grid">
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.33-8 4v2h16v-2c0-2.67-5.33-4-8-4z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Guest Name</span><span class="v-val" id="previewGuest">{{ $value('guest_name', 'Guest name') }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V9h10v2z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Confirmation Number</span><span class="v-val" id="previewReference">{{ $value('confirmation_reference', 'Reference') }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Pickup Date</span><span class="v-val" id="previewCheckIn">{{ $formattedCheckIn }} <span class="v-sub">(From {{ $pickupTime }})</span></span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Drop-off Date</span><span class="v-val" id="previewCheckOut">{{ $formattedCheckOut }} <span class="v-sub">(Until {{ $dropoffTime }})</span></span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm4.2 14.2L11 13V7h1.5v5.2l4.5 2.7-.8 1.3z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Trip Duration</span><span class="v-val" id="previewNightsPreview">{{ $days }} {{ $days == 1 ? 'Day' : 'Days' }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Vehicle Type</span><span class="v-val" id="previewVehicleType">{{ $value('vehicle_type', 'Select option') }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Passengers</span><span class="v-val" id="previewPassengers">{{ $value('passengers', '1') }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M17 4h-1V3c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1v1H7c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-7-1h4v1h-4V3zm7 15H7V6h10v12z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Luggage</span><span class="v-val" id="previewLuggageCount">{{ $value('luggage_count', '0') }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Meet & Greet</span><span class="v-val" id="previewMeetGreet">{{ $value('meet_greet') ? 'Yes' : 'No' }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Flight Number</span><span class="v-val" id="previewFlightNumber">{{ $value('flight_number', '—') }}</span></div>
                    </div>
<div class="v-field span2">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Special Requests</span><span class="v-val long" id="previewSpecialRequests">{{ $specialRequests }}</span></div>
                    </div>
                </div>
            </section>
            <div class="v-note">
                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                <span>Your driver will be waiting at arrivals holding a name board.</span>
            </div>
        </div>

        <!-- SIDE -->
        <aside class="v-side">
<div class="v-show">
                <div class="v-show-body">
                    <h3 class="v-hero" id="previewCompanyName">{{ $companyName }}</h3>
                    <div class="v-hero-sub">Transfer Provider</div>
                    <div class="v-route">
                        <div class="v-route-pt">
                            <div class="v-route-dot"></div>
                            <div><div class="v-route-lbl">Pickup</div><div class="v-route-val" id="previewPickupLocation">{{ $pickupLocation }}</div></div>
                        </div>
                        <div class="v-route-line"><svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg></div>
                        <div class="v-route-pt">
                            <div class="v-route-dot end"></div>
                            <div><div class="v-route-lbl">Drop-off</div><div class="v-route-val" id="previewDropoffLocation">{{ $dropoffLocation }}</div></div>
                        </div>
                    </div>
                    <div class="v-rows">
                        <div class="v-row">
                            <svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                            <span id="previewCompanyPhone">{{ $companyPhone }}</span>
                        </div>
                        @if ($value('company_email'))
                        <div class="v-row">
                            <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            <span id="previewCompanyEmail">{{ $value('company_email', '') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="v-show">
                @if($preview || $hasPhoto)
                    <div class="v-photo v-photo-sm">
                        <img id="previewCarPhoto" src="{{ $preview ? $heroImage : $carPhotoUrl }}" alt="Vehicle photo" data-hero-image="{{ $heroImage }}">
                        <div id="previewPhotoPlaceholder" class="v-photo-tag d-none">No photo included</div>
                    </div>
                @else
                    <div class="v-photo-empty v-photo-sm">
                        <svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                        <span>Vehicle photo not included</span>
                    </div>
                @endif
            </div>

            <div class="v-info">
                <div class="v-info-head">
                    <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-5.45 9-12V5l-9-4z"/></svg>
                    <h4>Insurance Coverage</h4>
                </div>
                <p id="previewInsuranceType">{{ $value('insurance_type', 'N/A') }}</p>
            </div>
        </aside>
    </div>

    <!-- FOOTER -->
    <div class="v-foot">
        <div class="v-fcol">
            <h5>{{ $settings->company_name }} Contact</h5>
            <div class="v-fline"><svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg><span>{{ $settings->phone ?? '+966 920022050' }}</span></div>
            <div class="v-fline"><svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg><span>{{ $settings->email ?? 'info@almatravel.co' }}</span></div>
            <div class="v-fline"><svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93C7.05 19.44 4 16.08 4 12c0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg><span>{{ $settings->website ?? 'www.almatravel.co' }}</span></div>
            <div class="v-fline"><svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 8.5c0 5.25 7 13 7 13s7-7.75 7-13C19 5.13 15.87 2 12 2zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg><span>{{ $settings->address ?? 'Riyadh, Saudi Arabia' }}</span></div>
        </div>
        <div class="v-fcol mid">
            <h5>Booking Consultant</h5>
            @if($preview)
                <div class="v-fline"><svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.33-8 4v2h16v-2c0-2.67-5.33-4-8-4z"/></svg><span id="previewConsultantName">Alma Travel Team</span></div>
                <div class="v-fline"><svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg><span id="previewConsultantEmail">Booking support</span></div>
                <div class="v-fline"><svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg><span id="previewConsultantPhone">{{ $settings->phone ?? '+966 920022050' }}</span></div>
            @elseif($employee)
                <div class="v-fline"><svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.33-8 4v2h16v-2c0-2.67-5.33-4-8-4z"/></svg><span id="previewConsultantName">{{ $employee->name }}</span></div>
                <div class="v-fline"><svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg><span id="previewConsultantEmail">{{ $employee->email }}</span></div>
                <div class="v-fline"><svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg><span id="previewConsultantPhone">{{ $employee->phone }}</span></div>
            @else
                <div class="v-fline"><svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.33-8 4v2h16v-2c0-2.67-5.33-4-8-4z"/></svg><span id="previewConsultantName">Alma Travel Team</span></div>
                <div class="v-fline"><svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg><span id="previewConsultantEmail">Booking support</span></div>
                <div class="v-fline"><svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg><span id="previewConsultantPhone">{{ $settings->phone ?? '+966 920022050' }}</span></div>
            @endif
        </div>
        <div class="v-fcol v-comp">
            <div class="t">With Compliments</div>
            <div class="s">{{ $settings->company_name }}</div>
        </div>
    </div>

</div>
