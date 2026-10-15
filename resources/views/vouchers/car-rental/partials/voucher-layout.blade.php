@php
    $preview = (bool) ($preview ?? false);
    $logoData = $logoData ?? null;
    $hotelPhotoData = $hotelPhotoData ?? null;
    $value = fn (string $key, string $fallback = '') => $preview ? $fallback : data_get($voucher, $key, $fallback);
    $formattedCheckIn = $preview ? 'Select date' : $voucher->check_in_date->format('d M Y');
    $formattedCheckOut = $preview ? 'Select date' : $voucher->check_out_date->format('d M Y');
    $pickupTime = $preview ? '10:00' : ($voucher->pickup_time ?? $voucher->check_in_time ?? '10:00');
    $dropoffTime = $preview ? '12:00' : ($voucher->dropoff_time ?? $voucher->check_out_time ?? '12:00');
    $rentalDuration = $preview ? null : $voucher->rental_duration;
    $durationText = $preview ? 'e.g. 4 Days' : ($rentalDuration ? $rentalDuration.' '.($rentalDuration == 1 ? 'Day' : 'Days') : '—');
    $branchTiming = $preview ? 'e.g. 24 Hours' : ($_bt ? (preg_match('/hour|hr/i', $_bt) ? $_bt : $_bt.' Hours') : '—');
    $flightNumber = $preview ? '—' : ($voucher->flight_number ?? '—');
    $flightAirport = $preview ? '—' : ($voucher->flight_airport ?? '—');
    $flightArrivalDate = $preview ? 'Select date' : ($voucher->flight_arrival_date ? $voucher->flight_arrival_date->format('d M Y') : '—');
    $flightArrivalTime = $preview ? '—' : ($voucher->flight_arrival_time ?? '—');
    $pickupLocation = $preview ? 'Pickup location' : ($voucher->pickup_location ?? '—');
    $dropoffLocation = $preview ? 'Drop-off location' : ($voucher->dropoff_location ?? '—');
    $vehicleType = $preview ? 'Select type' : ($voucher->vehicle_type ?? '—');
    $vehicleModel = $preview ? 'Vehicle model' : ($voucher->vehicle_model ?? '—');
    $insuranceType = $preview ? 'Select insurance' : ($voucher->insurance_type ?? '—');
    $supplierReference = $preview ? 'Supplier reference' : ($voucher->supplier_reference ?? '—');
    $importantNotes = $preview ? 'Add important notes here' : ($voucher->important_notes ?: '—');
    $termsText = $preview ? \App\Models\Voucher::defaultTerms('car-rental') : ($voucher->terms_text ?: \App\Models\Voucher::defaultTerms('car-rental'));
    $termsLines = collect(preg_split('/\r\n|\r|\n/', (string) $termsText))->map(fn ($l) => preg_replace('/^\s*\d+\.\s*/', '', trim($l)))->filter()->values();
    $pickupTerminal = $preview ? '' : ($voucher->pickup_terminal ?? '');
    $dropoffTerminal = $preview ? '' : ($voucher->dropoff_terminal ?? '');
    $bookingNumber = $preview ? '—' : ($voucher->booking_number ?? '—');
    $contactNumber = $preview ? '—' : ($voucher->contact_number ?? '—');
    $idPassport = $preview ? '—' : ($voucher->id_passport_number ?? '—');
    $employee = $preview ? null : $voucher->employee;
    $logoSource = $logoData ?? ($settings->logoUrl() ?? null);
    $hasCarPhoto = ! $preview && $voucher->include_hotel_photo && ($voucher->hotel_photo_path || $hotelPhotoData);
    $photoDisabled = ! $hasCarPhoto;
    $voucherNumber = $preview ? 'ALMA-CR-20260917-0001' : $voucher->voucher_number;
    $voucherDate = $preview ? now()->format('d M Y') : $voucher->created_at->format('d M Y');
    $carPhotoUrl = $hotelPhotoData ?? ($voucher ? ($voucher->hotelPhotoUrl() ?? 'https://images.unsplash.com/photo-1632476800512-2b2c8ea5f000?auto=format&fit=crop&w=1000&q=80') : 'https://images.unsplash.com/photo-1632476800512-2b2c8ea5f000?auto=format&fit=crop&w=1000&q=80');
    $heroImage = asset('images/vouchers/car-rental-hero.jpg');
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
    .voucher-card.theme-maroon {
        --vhead-bg: var(--vgrad);
        --vp: #6B1A1A;
        --vpd: #3D0D0D;
        --va: #D4AF37;
        --va-soft: rgba(212, 175, 55, 0.13);
        --va-line: rgba(212, 175, 55, 0.42);
        --vgrad: linear-gradient(118deg, #3D0D0D 0%, #6B1A1A 55%, #8B2424 100%);
        --vbrand: #ffffff;
    }
    .voucher-card.theme-crimson {
        --vp: #A93226;
        --vpd: #7B241C;
        --va: #CD7F32;
        --va-soft: rgba(205, 127, 50, 0.13);
        --va-line: rgba(205, 127, 50, 0.42);
        --vgrad: linear-gradient(118deg, #7B241C 0%, #A93226 55%, #C0392B 100%);
        --vhead-bg: var(--vgrad);
        --vbrand: #ffffff;
    }
    .voucher-card.theme-slate {
        --vp: #64748B;
        --vpd: #475569;
        --va: #94A3B8;
        --va-soft: rgba(148, 163, 184, 0.15);
        --va-line: rgba(148, 163, 184, 0.45);
        --vgrad: linear-gradient(118deg, #475569 0%, #64748B 55%, #94A3B8 100%);
        --vhead-bg: var(--vgrad);
        --vbrand: #ffffff;
    }
    /* Slate: red icons in header/footer */
    .voucher-card.theme-slate .v-head,
    .voucher-card.theme-slate .v-foot {
        --va: #C0392B;
        --va-soft: rgba(192, 57, 43, 0.13);
        --va-line: rgba(192, 57, 43, 0.42);
    }
    .voucher-card.theme-premiumred {
        --vp: #8B251E;
        --vpd: #5F1812;
        --va: #C0392B;
        --va-soft: rgba(192, 57, 43, 0.13);
        --va-line: rgba(192, 57, 43, 0.42);
        --vgrad: linear-gradient(118deg, #5F1812 0%, #8B251E 55%, #B5322B 100%);
        --vhead-bg: var(--vgrad);
        --vbrand: #ffffff;
    }
    /* Premium Red: red icons in body only, header/footer keep gold */
    .voucher-card.theme-premiumred .v-head,
    .voucher-card.theme-premiumred .v-foot {
        --va: #D4AF37;
        --va-soft: rgba(212, 175, 55, 0.13);
        --va-line: rgba(212, 175, 55, 0.42);
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
    .v-val.long { font-size: 11px; font-weight: 500; white-space: pre-line; }

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

    /* ============ FIXED RENTAL TERMS (compact) ============ */
    .v-fixed-notes {
        margin: 2px 0 0;
        padding-left: 15px;
        font-size: 8px;
        line-height: 1.55;
        color: var(--vmut);
        column-gap: 18px;
    }
    .v-fixed-notes li { margin-bottom: 2px; break-inside: avoid; }
    @media print { .v-fixed-notes { font-size: 7.5px; } }

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
        /* Hide form panels completely (no space taken) - fixes multi-page blank output */
        .form-panel, .preview-hint, .preview-actions, .voucher-nav, .gen-header { display: none !important; }
        body * { visibility: hidden; }
        #printVoucher, #printVoucher * { visibility: visible; }
        #printVoucher {
            position: absolute;
            left: 0;
            top: 0;
            width: 297mm;
            margin: 0;
            page-break-inside: avoid;
        }
        /* compact fields so the voucher fits a single A4 landscape page (no zoom) */
        .v-head { min-height: 28mm; padding-top: 6px; padding-bottom: 6px; }
        .v-body { padding-top: 8px; padding-bottom: 8px; }
        .v-main, .v-side { gap: 6px; }
        .v-sec-title { margin-bottom: 4px; font-size: 9px; }
        .v-field { padding: 5px 8px; }
        .v-label { font-size: 7.5px; }
        .v-val { font-size: 10px; }
        .v-photo { height: 110px; }
        .v-journey { padding: 8px 10px; }
        .v-note { padding: 8px 10px; font-size: 10px; }
        .v-info { padding: 8px 10px; }
        .v-fixed-notes { font-size: 7px; }
        .v-fixed-notes li { margin-bottom: 1px; }
        .v-foot { padding-top: 6px; padding-bottom: 6px; }
        .v-fline { font-size: 9px; }
    }
    .d-none { display: none !important; }

    /* ============ JOURNEY TIMELINE (car rental) ============ */
    .v-journey {
        display: grid; grid-template-columns: 1fr 40px 1fr; gap: 6px 8px; align-items: center;
        background: var(--vpanel); border: 1px solid var(--vline); border-radius: 10px;
        padding: 10px 14px;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .v-j-tag {
        display: inline-block; font-size: 8px; font-weight: 700; letter-spacing: 1.6px; text-transform: uppercase;
        color: var(--vp); background: var(--va-soft); border: 1px solid var(--va-line);
        padding: 3px 9px; border-radius: 20px; margin-bottom: 6px;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .v-j-date { font-size: 12.5px; font-weight: 700; color: var(--vink); }
    .v-j-date .v-sub { font-size: 9.5px; font-weight: 400; color: var(--vmut); }
    .v-j-loc { display: flex; align-items: flex-start; gap: 7px; margin-top: 6px; font-size: 11px; font-weight: 600; color: var(--vink); line-height: 1.4; }
    .v-j-loc svg { width: 13px; height: 13px; fill: var(--va); flex: 0 0 13px; margin-top: 1px; }
    .v-j-term { display: flex; align-items: center; gap: 7px; margin-top: 5px; font-size: 10px; color: var(--vmut); }
    .v-j-term svg { width: 12px; height: 12px; fill: var(--va); flex: 0 0 12px; }
    .v-j-arrow { display: flex; align-items: center; justify-content: center; }
    .v-j-arrow svg { width: 24px; height: 24px; fill: var(--va); }
    .v-j-dur {
        grid-column: span 3; display: flex; align-items: center; gap: 10px;
        border-top: 1px dashed var(--va-line); padding-top: 8px; margin-top: 2px;
    }
    .v-j-dur .v-val { font-size: 12px; }
    .v-j-dur svg { width: 15px; height: 15px; fill: var(--va); flex: 0 0 15px; }
    .v-vgrid { display: grid; grid-template-columns: 1fr 1fr; gap: 9px 10px; }
    .v-vgrid .v-val { font-size: 11.5px; }


    /* ============ DOMPDF PDF DOWNLOAD — single A4 landscape page ============ */
    /* DomPDF lacks flexbox/grid + gradients: rebuild with floats/tables, solid colors, compact. */
    .voucher-pdf #printVoucher { position: static !important; max-height: none !important; overflow: visible !important; width: 100% !important; }
    .voucher-pdf .voucher-card { width: 100% !important; min-height: 0 !important; }
    .voucher-pdf .v-head { display: table !important; width: 100% !important; min-height: 0 !important; padding: 4px 20px !important; background: #0B3B3E !important; border-bottom: 2px solid #C8A24B !important; }
    .voucher-pdf .voucher-card.theme-modern .v-head { background: #F7F2E7 !important; }
    .voucher-pdf .voucher-card.theme-premium .v-head { background: #8B251E !important; }
    .voucher-pdf .voucher-card.theme-maroon .v-head { background: #6B1A1A !important; }
    .voucher-pdf .voucher-card.theme-crimson .v-head { background: #A93226 !important; }
    .voucher-pdf .voucher-card.theme-slate .v-head { background: #64748B !important; }
    .voucher-pdf .v-head > div { display: table-cell !important; vertical-align: middle !important; }
    .voucher-pdf .v-brand-name { font-size: 14px !important; }
    .voucher-pdf .v-brand-tag { font-size: 6px !important; margin-top: 2px !important; }
    .voucher-pdf .v-title h2 { font-size: 12px !important; margin: 0 !important; }
    .voucher-pdf .v-title p { font-size: 6px !important; margin: 0 !important; }
    .voucher-pdf .v-badge { width: 24px !important; height: 24px !important; margin-bottom: 2px !important; }
    .voucher-pdf .v-meta .lbl, .voucher-pdf .v-meta .dt { font-size: 6px !important; }
    .voucher-pdf .v-meta .num { font-size: 8px !important; }
    .voucher-pdf .v-barcode { height: 12px !important; }
    .voucher-pdf .v-body { display: block !important; padding: 5px 14px 5px !important; }
    .voucher-pdf .v-body::after { content: ''; display: table; clear: both; }
    .voucher-pdf .v-main { display: block !important; float: left !important; width: 58% !important; }
    .voucher-pdf .v-side { display: block !important; float: right !important; width: 40% !important; }
    .voucher-pdf .v-sec { margin-bottom: 5px !important; }
    .voucher-pdf .v-sec-title { font-size: 7px !important; margin-bottom: 2px !important; }
    .voucher-pdf .v-grid { display: block !important; }
    .voucher-pdf .v-grid::after { content: ''; display: table; clear: both; }
    .voucher-pdf .v-grid > * { float: left !important; width: 48% !important; margin: 0 2% 4px 0 !important; }
    .voucher-pdf .v-field { padding: 3px 5px !important; border-radius: 6px !important; }
    .voucher-pdf .v-label { font-size: 6px !important; }
    .voucher-pdf .v-val { font-size: 8px !important; }
    .voucher-pdf .v-journey { display: block !important; padding: 5px 7px !important; margin-bottom: 5px !important; border-radius: 6px !important; }
    .voucher-pdf .v-photo { height: 50px !important; }
    .voucher-pdf .v-hero { font-size: 11px !important; padding: 4px 8px !important; }
    .voucher-pdf .v-hero-sub { font-size: 7px !important; }
    .voucher-pdf .v-info { padding: 5px 7px !important; margin-bottom: 5px !important; border-radius: 6px !important; }
    .voucher-pdf .v-info-head { font-size: 7px !important; margin-bottom: 3px !important; }
    .voucher-pdf .v-fixed-notes { font-size: 5.5px !important; line-height: 1.25 !important; }
    .voucher-pdf .v-fixed-notes li { margin-bottom: 1px !important; }
    .voucher-pdf .v-note { padding: 4px 7px !important; font-size: 7.5px !important; margin-bottom: 5px !important; }
    .voucher-pdf .v-foot { display: table !important; width: 100% !important; clear: both !important; padding: 4px 14px !important; background: #0B3B3E !important; border-top: 2px solid #C8A24B !important; }
    .voucher-pdf .voucher-card.theme-modern .v-foot, .voucher-pdf .voucher-card.theme-premium .v-foot { background: #8B251E !important; }
    .voucher-pdf .voucher-card.theme-maroon .v-foot { background: #6B1A1A !important; }
    .voucher-pdf .voucher-card.theme-crimson .v-foot { background: #A93226 !important; }
    .voucher-pdf .voucher-card.theme-slate .v-foot { background: #64748B !important; }
    .voucher-pdf .v-fcol { display: table-cell !important; vertical-align: middle !important; }
    .voucher-pdf .v-fcol h5 { font-size: 6.5px !important; margin: 0 0 2px !important; }
    .voucher-pdf .v-fline { font-size: 6.5px !important; margin-bottom: 1px !important; }
    .voucher-pdf .v-fline svg { width: 8px !important; height: 8px !important; }
</style>

<div class="voucher-card theme-{{ $preview ? 'premium' : (($theme = $voucher?->voucher_theme ?? $settings?->voucher_theme ?? 'premium') === 'alma_premium' ? 'premium' : $theme) }} {{ $preview ? 'voucher-preview' : 'voucher-final' }} {{ $photoDisabled ? 'voucher-photo-disabled' : '' }}" id="printVoucher">

    <!-- HEADER -->
    <div class="v-head">
        <div class="v-brand">
            @if($logoSource)
                <img src="{{ $logoSource }}" alt="{{ $settings->company_name }} logo">
            @endif
            <div>
                <div class="v-brand-name">{{ $settings->company_name }}</div>
                <div class="v-brand-tag">MORE THAN A JOURNEY</div>
            </div>
        </div>
        <div class="v-title">
            <div class="v-badge"><svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg></div>
            <h2>Car Rental Voucher</h2>
            <p>We Always Care</p>
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
                        <div class="v-tx"><span class="v-label">Booking Number</span><span class="v-val" id="previewBookingNumber">{{ $bookingNumber }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Confirmation</span><span class="v-val" id="previewReference">{{ $value('confirmation_reference', '—') }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg></div>
                        <div class="v-tx"><span class="v-label">ID / Passport</span><span class="v-val" id="previewIdPassport">{{ $idPassport }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Contact Number</span><span class="v-val" id="previewContactNumber">{{ $contactNumber }}</span></div>
                    </div>
                </div>
            </section>
<section>
                <h3 class="v-sec-title">Flight Number</h3>
                <div class="v-grid">
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Flight Number</span><span class="v-val" id="previewFlightNumber">{{ $flightNumber }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 8.5c0 5.25 7 13 7 13s7-7.75 7-13C19 5.13 15.87 2 12 2zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Arrival Airport</span><span class="v-val" id="previewFlightAirport">{{ $flightAirport }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V9h10v2z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Arrival Date</span><span class="v-val" id="previewFlightArrivalDate">{{ $flightArrivalDate }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm4.2 14.2L11 13V7h1.5v5.2l4.5 2.7-.8 1.3z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Arrival Time</span><span class="v-val" id="previewFlightArrivalTime">{{ $flightArrivalTime }}</span></div>
                    </div>
                </div>
            </section>
<section>
                <h3 class="v-sec-title">Pickup &amp; Drop-off</h3>
                <div class="v-journey">
                    <div class="v-j-leg">
                        <span class="v-j-tag">Pickup</span>
                        <div class="v-j-date" id="previewCheckIn">{{ $formattedCheckIn }} <span class="v-sub">(From {{ $pickupTime }})</span></div>
                        <div class="v-j-loc"><svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 8.5c0 5.25 7 13 7 13s7-7.75 7-13C19 5.13 15.87 2 12 2zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg><span id="previewPickupLocation">{{ $pickupLocation }}</span></div>
                        @if ($pickupTerminal)
                        <div class="v-j-term"><svg viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg><span id="previewPickupTerminal">{{ $pickupTerminal }}</span></div>
                        @else
                        <div class="v-j-term d-none" id="previewPickupTerminalCard"><svg viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg><span id="previewPickupTerminal"></span></div>
                        @endif
                    </div>
                    <div class="v-j-arrow"><svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg></div>
                    <div class="v-j-leg">
                        <span class="v-j-tag">Drop-off</span>
                        <div class="v-j-date" id="previewCheckOut">{{ $formattedCheckOut }} <span class="v-sub">(Until {{ $dropoffTime }})</span></div>
                        <div class="v-j-loc"><svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 8.5c0 5.25 7 13 7 13s7-7.75 7-13C19 5.13 15.87 2 12 2zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg><span id="previewDropoffLocation">{{ $dropoffLocation }}</span></div>
                        @if ($dropoffTerminal)
                        <div class="v-j-term"><svg viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg><span id="previewDropoffTerminal">{{ $dropoffTerminal }}</span></div>
                        @else
                        <div class="v-j-term d-none" id="previewDropoffTerminalCard"><svg viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg><span id="previewDropoffTerminal"></span></div>
                        @endif
                    </div>
                    <div class="v-j-dur">
                        <svg viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm4.2 14.2L11 13V7h1.5v5.2l4.5 2.7-.8 1.3z"/></svg>
                        <div><span class="v-label">Rental Duration</span><span class="v-val" id="previewRentalDuration">{{ $durationText }}</span></div>
                    </div>
                </div>
            </section>
<section>
                <h3 class="v-sec-title">Important Notes</h3>
                <div class="v-grid">
<div class="v-field span2">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Notes</span><span class="v-val long" id="previewImportantNotes">{{ $importantNotes }}</span></div>
                    </div>
                </div>
            </section>
<div class="v-note">
                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                <span>Please present a valid driving licence and this voucher at pickup.</span>
            </div>
        </div>

        <!-- SIDE: COMPANY SHOWCASE -->
        <aside class="v-side">
            <div class="v-show">
                @if($preview || $hasCarPhoto)
                    <div class="v-photo">
                        <img id="previewCarPhoto" src="{{ $preview ? $heroImage : $carPhotoUrl }}" alt="Vehicle photo" data-hero-image="{{ $heroImage }}">
                        <div id="previewPhotoPlaceholder" class="v-photo-tag d-none">No photo included</div>
                    </div>
                @else
                    <div class="v-photo">
                        <img src="{{ !empty($heroImageData) ? $heroImageData : $heroImage }}" alt="Photo">
                    </div>
                @endif
                <div class="v-show-body">
                    <h3 class="v-hero" id="previewHeroVehicleType">{{ $vehicleType }}</h3>
                    <div class="v-hero-sub">Rented Vehicle</div>
                    <div class="v-rows">
                        <div class="v-row">
                            <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 8.5c0 5.25 7 13 7 13s7-7.75 7-13C19 5.13 15.87 2 12 2zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg>
                            <span id="previewPickupLocation2">{{ $pickupLocation }}</span>
                        </div>
                        <div class="v-row">
                            <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 8.5c0 5.25 7 13 7 13s7-7.75 7-13C19 5.13 15.87 2 12 2zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg>
                            <span id="previewDropoffLocation2">{{ $dropoffLocation }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="v-info">
                <div class="v-info-head">
                    <svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                    <h4>Vehicle Details</h4>
                </div>
                <div class="v-vgrid">
                    <div><span class="v-label">Vehicle Type</span><span class="v-val" id="previewVehicleType">{{ $vehicleType }}</span></div>
                    <div><span class="v-label">Model / Similar</span><span class="v-val" id="previewVehicleModel">{{ $vehicleModel }}</span></div>
                    <div><span class="v-label">Insurance</span><span class="v-val" id="previewInsuranceType">{{ $insuranceType }}</span></div>
                    <div><span class="v-label">Supplier Ref.</span><span class="v-val" id="previewSupplierReference">{{ $supplierReference }}</span></div>
                </div>
            </div>

            <div class="v-info">
                <div class="v-info-head">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                    <h4>Rental Terms</h4>
                </div>
                <ol class="v-fixed-notes" id="previewTermsList">
                    @foreach ($termsLines as $line)
                    <li>{{ $line }}</li>
                    @endforeach
                </ol>
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


