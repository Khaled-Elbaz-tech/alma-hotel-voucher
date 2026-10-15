@php
    $preview = (bool) ($preview ?? false);
    $logoData = $logoData ?? null;
    $hotelPhotoData = $hotelPhotoData ?? null;

    $value = fn (string $key, string $fallback = '') => $preview ? $fallback : data_get($voucher, $key, $fallback);
    $fmtTime = function ($t) {
        if (! $t) return '—';
        try {
            return \Carbon\Carbon::createFromFormat('H:i', substr($t, 0, 5))->format('h:i A');
        } catch (\Exception $e) {
            return $t;
        }
    };
    $fmtDate = function ($d, $fallback = 'Select date') {
        if (! $d) return $fallback;
        try {
            return \Carbon\Carbon::parse($d)->format('d M Y');
        } catch (\Exception $e) {
            return $fallback;
        }
    };
    $isRoundTrip = $preview ? false : (($voucher->trip_type ?? 'one_way') === 'round_trip');
    $tripTypeLabel = $preview ? 'One Way' : ($isRoundTrip ? 'Round Trip' : 'One Way');
    $bookingDate = $preview ? 'Select date' : ($voucher->booking_date ? $voucher->booking_date->format('d M Y') : '—');
    $guestMobile = $preview ? '—' : ($voucher->guest_mobile ?? '—');
    $driverName = $preview ? '—' : ($voucher->driver_name ?? '—');
    $driverNumber = $preview ? '—' : ($voucher->driver_number ?? '—');
    $flightNumber = $preview ? '—' : ($voucher->flight_number ?: '—');
    $flightDate = $preview ? 'Select date' : ($voucher->flight_date ? $voucher->flight_date->format('d M Y') : '—');
    $flightTime = $preview ? '—' : $fmtTime($voucher->flight_time ?? null);
    $flightDetailsText = $preview ? '—' : trim($flightNumber.' · '.$flightDate.' · '.$flightTime, ' ·');
    $pickupDateTime = ($preview ? 'Select date' : $fmtDate($voucher->check_in_date)) . ' · ' . ($preview ? '—' : $fmtTime($voucher->check_in_time ?? null));
    $dropoffDateTime = ($preview ? 'Select date' : $fmtDate($voucher->check_out_date)) . ' · ' . ($preview ? '—' : $fmtTime($voucher->check_out_time ?? null));
    $returnPickupDateTime = $preview ? 'Select date · —' : ($voucher->return_pickup_date ? $fmtDate($voucher->return_pickup_date).' · '.$fmtTime($voucher->return_pickup_time ?? null) : '—');
    $returnDropoffDateTime = $preview ? 'Select date · —' : ($voucher->return_dropoff_date ? $fmtDate($voucher->return_dropoff_date).' · '.$fmtTime($voucher->return_dropoff_time ?? null) : '—');
    $returnFlightNumber = $preview ? '—' : ($voucher->return_flight_number ?: '—');
    $returnFlightDate = $preview ? 'Select date' : ($voucher->return_flight_date ? $voucher->return_flight_date->format('d M Y') : '—');
    $returnFlightTime = $preview ? '—' : $fmtTime($voucher->return_flight_time ?? null);
    $returnFlightDetailsText = $preview ? '—' : trim($returnFlightNumber.' · '.$returnFlightDate.' · '.$returnFlightTime, ' ·');
    $pickupLocation = $preview ? 'Pickup location' : ($voucher->pickup_location ?? '—');
    $dropoffLocation = $preview ? 'Drop-off location' : ($voucher->dropoff_location ?? '—');
    $vehicleType = $preview ? 'Select vehicle type' : ($voucher->vehicle_type ?? '—');
    $passengers = $preview ? '1' : ($voucher->passengers ?? '—');
    $luggageCount = $preview ? '0' : ($voucher->luggage_count ?? 0);
    $specialRequests = $preview ? 'Special requests' : ($voucher->special_requests ?: '—');
    $termsText = $preview ? \App\Models\Voucher::defaultTerms('transfers') : ($voucher->terms_text ?: \App\Models\Voucher::defaultTerms('transfers'));
    $termsLines = collect(preg_split('/\r\n|\r|\n/', (string) $termsText))->map(fn ($l) => preg_replace('/^\s*\d+\.\s*/', '', trim($l)))->filter()->values();
    $employee = $preview ? null : $voucher->employee;
    $logoSource = $logoData ?? ($settings->logoUrl() ?? null);
    $hasPhoto = ! $preview && $voucher->include_hotel_photo && ($voucher->hotel_photo_path || $hotelPhotoData);
    $photoDisabled = ! $hasPhoto;
    $voucherNumber = $preview ? 'ALMA-TR-20260917-0001' : $voucher->voucher_number;
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
    .v-brand .v-logos-row { display: flex; align-items: center; gap: 14px; }
    .v-brand .v-logo-main { max-height: 42px; max-width: 140px; object-fit: contain; }
    .v-brand .v-logo-lh { max-height: 32px; max-width: 120px; object-fit: contain; }
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
        width: 24px;
        height: 24px;
        flex: 0 0 24px;
        border-radius: 6px;
        background: var(--va-soft);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .v-ico svg { width: 13px; height: 13px; fill: var(--va); }
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

    /* ============ FIXED TERMS (compact) ============ */
    .v-fixed-notes {
        margin: 2px 0 0;
        padding-left: 15px;
        font-size: 8px;
        line-height: 1.55;
        color: var(--vmut);
    }
    .v-fixed-notes li { margin-bottom: 2px; break-inside: avoid; }

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
        /* One-way: 5% shrink, round-trip: 12% shrink — both fit one page */
        #printVoucher:not(.is-roundtrip) {
            zoom: 0.95;
            left: 50%;
            transform: translateX(-50%);
        }
        #printVoucher.is-roundtrip {
            zoom: 0.88;
            left: 50%;
            transform: translateX(-50%);
        }

        /* compact spacing so the voucher fits a single A4 landscape page */
        .v-head { min-height: 30mm; padding-top: 8px; padding-bottom: 8px; }
        .v-body { padding-top: 10px; padding-bottom: 10px; }
        .v-main, .v-side { gap: 8px; }
        .v-sec-title { margin-bottom: 6px; }
        .v-photo { height: 128px; }
        .v-foot { padding-top: 8px; padding-bottom: 8px; }
        .v-fixed-notes li { margin-bottom: 1px; }
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

<div class="voucher-card theme-{{ $preview ? 'premium' : (($theme = $voucher?->voucher_theme ?? $settings?->voucher_theme ?? 'premium') === 'alma_premium' ? 'premium' : $theme) }} {{ $preview ? 'voucher-preview' : 'voucher-final' }} {{ $photoDisabled ? 'voucher-photo-disabled' : '' }} {{ (($voucher?->trip_type ?? "") === "round_trip") ? "is-roundtrip" : "" }}" id="printVoucher">

    <!-- HEADER -->
    <div class="v-head">
        <div class="v-brand">
            <div class="v-logos-row">
                <img src="{{ asset('images/logos/alma-main.png') }}" alt="Alma Travel" class="v-logo-main">
                <img src="{{ asset('images/logos/lufthansa.png') }}" alt="Lufthansa City Center" class="v-logo-lh">
            </div>
            <div class="v-brand-tag">MORE THAN A JOURNEY</div>
        </div>
        <div class="v-title">
            <div class="v-badge"><svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg></div>
            <h2>Transfers Voucher</h2>
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
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Trip Type</span><span class="v-val" id="previewTripType">{{ $tripTypeLabel }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.33-8 4v2h16v-2c0-2.67-5.33-4-8-4z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Guest Name</span><span class="v-val" id="previewGuest">{{ $value('guest_name', 'Guest name') }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V9h10v2z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Confirmation Number</span><span class="v-val" id="previewReference">{{ $value('confirmation_reference', 'Reference') }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Flight Number</span><span class="v-val" id="previewFlightDetails">{{ $flightDetailsText }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Booking Date</span><span class="v-val" id="previewBookingDate">{{ $bookingDate }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Guest Mobile</span><span class="v-val" id="previewGuestMobile">{{ $guestMobile }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Number of Passengers</span><span class="v-val" id="previewPassengers">{{ $value('passengers', '1') }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M17 4h-1V3c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1v1H7c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-7-1h4v1h-4V3zm7 15H7V6h10v12z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Luggage</span><span class="v-val" id="previewLuggageCount">{{ $value('luggage_count', '0') }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Vehicle Type</span><span class="v-val" id="previewVehicleType">{{ $value('vehicle_type', 'Select option') }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.33-8 4v2h16v-2c0-2.67-5.33-4-8-4z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Driver Name</span><span class="v-val" id="previewDriverName">{{ $driverName }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Driver Number</span><span class="v-val" id="previewDriverNumber">{{ $driverNumber }}</span></div>
                    </div>
                </div>
            </section>
<section>
                <h3 class="v-sec-title">Transfer Details</h3>
                <div class="v-grid">
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 8.5c0 5.25 7 13 7 13s7-7.75 7-13C19 5.13 15.87 2 12 2zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Pickup</span><span class="v-val" id="previewPickupLocation">{{ $pickupLocation }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Pickup Date &amp; Time</span><span class="v-val" id="previewCheckIn">{{ $pickupDateTime }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 8.5c0 5.25 7 13 7 13s7-7.75 7-13C19 5.13 15.87 2 12 2zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Drop-off</span><span class="v-val" id="previewDropoffLocation">{{ $dropoffLocation }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Drop-off Date &amp; Time</span><span class="v-val" id="previewCheckOut">{{ $dropoffDateTime }}</span></div>
                    </div>
                </div>
            </section>
<section id="previewReturnBlock" class="{{ $preview ? 'd-none' : ($isRoundTrip ? '' : 'd-none') }}">
                <h3 class="v-sec-title">Return Transfer</h3>
                <div class="v-grid">
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 8.5c0 5.25 7 13 7 13s7-7.75 7-13C19 5.13 15.87 2 12 2zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Return Pickup</span><span class="v-val" id="previewReturnPickupLocation">{{ $preview ? '—' : ($voucher->return_pickup_location ?? '—') }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Pickup Date &amp; Time</span><span class="v-val" id="previewReturnPickup">{{ $returnPickupDateTime }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 8.5c0 5.25 7 13 7 13s7-7.75 7-13C19 5.13 15.87 2 12 2zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Return Drop-off</span><span class="v-val" id="previewReturnDropoffLocation">{{ $preview ? '—' : ($voucher->return_dropoff_location ?? '—') }}</span></div>
                    </div>
<div class="v-field">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Drop-off Date &amp; Time</span><span class="v-val" id="previewReturnDropoff">{{ $returnDropoffDateTime }}</span></div>
                    </div>
<div class="v-field span2">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Return Flight Number</span><span class="v-val" id="previewReturnFlightDetails">{{ $returnFlightDetailsText }}</span></div>
                    </div>
                </div>
            </section>
            <section>
                <h3 class="v-sec-title">Special Requests</h3>
                <div class="v-grid">
<div class="v-field span2">
                        <div class="v-ico"><svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg></div>
                        <div class="v-tx"><span class="v-label">Requests</span><span class="v-val long" id="previewSpecialRequests">{{ $specialRequests }}</span></div>
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
                    <h3 class="v-hero" id="previewDriverName">{{ $driverName }}</h3>
                    <div class="v-hero-sub">Driver Details</div>
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
                            <span id="previewDriverNumber">{{ $driverNumber }}</span>
                        </div>
                        <div class="v-row">
                            <svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                            <span id="previewSideVehicleType">{{ $vehicleType }}</span>
                        </div>
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
                    <div class="v-photo">
                        <img src="{{ !empty($heroImageData) ? $heroImageData : $heroImage }}" alt="Photo">
                    </div>
                @endif
            </div>

            <div class="v-info">
                <div class="v-info-head">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                    <h4>Terms &amp; Conditions</h4>
                </div>
                <ul class="v-fixed-notes" id="previewTermsList">
                    @foreach ($termsLines as $line)
                    <li>{{ $line }}</li>
                    @endforeach
                </ul>
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
