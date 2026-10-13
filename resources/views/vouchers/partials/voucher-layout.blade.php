@php
    $preview = (bool) ($preview ?? false);
    $logoData = $logoData ?? null;
    $hotelPhotoData = $hotelPhotoData ?? null;

    $value = fn (string $key, string $fallback = '') => $preview ? $fallback : data_get($voucher, $key, $fallback);
    $formattedCheckIn = $preview ? 'Select date' : $voucher->check_in_date->format('d M Y');
    $formattedCheckOut = $preview ? 'Select date' : $voucher->check_out_date->format('d M Y');
    $checkInTime = $preview ? '15:00' : ($voucher->check_in_time ?? '15:00');
    $checkOutTime = $preview ? '12:00' : ($voucher->check_out_time ?? '12:00');
    $nights = $preview ? 0 : $voucher->nights_count;
    $hotelName = $preview ? 'Hotel name' : $voucher->hotel_name;
    $hotelAddress = $preview ? 'Hotel address' : $voucher->hotel_address;
    $hotelPhone = $preview ? 'Hotel phone' : ($voucher->hotel_phone ?: '—');
    $occupancy = $preview ? '1 Room | 2 Adults' : ($voucher->occupancy ?: '1 Room | 2 Adults');
    $specialRequests = $preview ? 'Special requests' : ($voucher->special_requests ?: '—');
    $policy = $preview ? 'Select a policy' : $voucher->cancellation_policy;
    $employee = $preview ? null : $voucher->employee;
    $logoSource = $logoData ?? ($settings->logoUrl() ?? null);
    $hasHotelPhoto = ! $preview && $voucher->include_hotel_photo && ($voucher->hotel_photo_path || $hotelPhotoData);
    $photoDisabled = ! $hasHotelPhoto;
    $voucherNumber = $preview ? 'ALMA-HV-20260917-0001' : $voucher->voucher_number;
    $voucherDate = $preview ? now()->format('d M Y') : $voucher->created_at->format('d M Y');
    $hotelPhotoUrl = $hotelPhotoData ?? ($voucher ? ($voucher->hotelPhotoUrl() ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1000&q=80') : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1000&q=80');
    $hotelLocationSub = $preview ? '' : ($voucher ? ($voucher->hotel_location_sub ?? '') : '');
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Playfair+Display:ital,wght@0,600;1,400&family=Montserrat:wght@400;600&display=swap');

    @page {
      size: A4 landscape;
      margin: 0;
    }

    .voucher-card {
      width: 297mm;
      min-height: 210mm;
      background: #fcfcf9;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      position: relative;
      overflow: hidden;
      margin: 0 auto;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }

    /* HEADER */
    .voucher-header {
      background-color: #063B3E;
      height: 38mm;
      display: grid;
      grid-template-columns: 1fr 1.8fr 1fr;
      align-items: center;
      padding: 0 25px;
      border-bottom: 2px solid #C89B3C;
      color: #ffffff;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }

    .brand-logo {
      display: flex;
      align-items: center;
      gap: 0;
    }

    .logo-text-group {
      display: flex;
      flex-direction: column;
    }

    .logo-main {
      font-family: 'Playfair Display', serif;
      font-size: 26px;
      letter-spacing: 1px;
      color: #ffffff;
      line-height: 1;
    }

    .logo-sub {
      font-size: 9px;
      letter-spacing: 2px;
      color: #C89B3C;
      margin-top: 4px;
      text-transform: uppercase;
    }

    .header-center {
      text-align: center;
      border-left: 1px solid rgba(200, 155, 60, 0.4);
      border-right: 1px solid rgba(200, 155, 60, 0.4);
      padding: 0 15px;
    }

    .voucher-title {
      font-family: 'Georgia', serif !important;
      font-size: 18px !important;
      letter-spacing: 3px;
      color: #C89B3C;
      font-weight: 700;
    }

    .voucher-subtitle {
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-size: 15px;
      color: #ffffff;
      margin-top: 4px;
    }

    .header-right {
      text-align: right;
      font-size: 9px;
      letter-spacing: 1.5px;
      color: #d1d5db;
      line-height: 1.8;
      text-transform: uppercase;
    }

    /* MAIN CONTENT */
    .voucher-body {
      display: grid;
      grid-template-columns: 1.15fr 1fr;
      gap: 16px;
      padding: 16px 22px;
      min-height: 135mm;
      margin-bottom: 24px;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }

    /* LEFT SECTION - BOOKING DETAILS */
    .section-box {
      border: 1px solid #e2d9c8;
      border-radius: 4px;
      padding: 12px;
      background-color: #faf8f5;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }

    .section-title {
      font-family: 'Cinzel', serif;
      font-size: 15px;
      letter-spacing: 2px;
      color: #063B3E;
      font-weight: 700;
      margin-bottom: 12px;
      text-transform: uppercase;
    }

    .details-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }

    .grid-card {
      background: #ffffff;
      border: 1px solid #e8e2d5;
      border-radius: 4px;
      padding: 8px 10px;
      display: flex;
      align-items: center;
      gap: 10px;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }

    .grid-card.full-width {
      grid-column: span 2;
    }

    .card-icon {
      width: 26px;
      height: 26px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #C89B3C;
      flex-shrink: 0;
    }

    .card-icon svg {
      width: 20px;
      height: 20px;
      fill: currentColor;
    }

    .card-content {
      display: flex;
      flex-direction: column;
    }

    .card-label {
      font-family: 'Montserrat', sans-serif;
      font-size: 9px;
      font-weight: 700;
      color: #C89B3C;
      text-transform: uppercase;
      letter-spacing: 0.8px;
    }

    .card-value {
      font-family: 'Georgia', serif !important;
      font-size: 17px !important;
      font-weight: 700;
      color: #111111;
      margin-top: 2px;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }

    .card-value sub-text {
      font-size: 10px;
      color: #666;
      font-weight: 400;
    }

    .card-value,
    .detail-value,
    .hotel-name,
    .voucher-title {
      font-family: 'Georgia', serif !important;
      font-size: 17px !important;
      font-weight: 700;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }

    /* RIGHT SECTION - HOTEL SHOWCASE */
    .hotel-showcase {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .hotel-header-flex {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    .hotel-name {
      font-family: 'Georgia', serif !important;
      font-size: 23px;
      font-weight: 700;
      color: #063B3E;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }

    .hotel-sub-location {
      font-size: 10px;
      letter-spacing: 1px;
      color: #C89B3C;
      font-weight: 700;
      text-transform: uppercase;
      margin-top: 2px;
    }

    .hotel-contact-info {
      font-size: 10px;
      color: #555555;
      margin-top: 6px;
      line-height: 1.4;
    }

    .hotel-logo-img {
      max-width: 90px;
      max-height: 40px;
      object-fit: contain;
    }

    .hotel-image-container {
      width: 100%;
      height: 165px;
      border-radius: 4px;
      overflow: hidden;
      border: 1px solid #C89B3C;
      position: relative;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }

    .hotel-image-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .hotel-image-placeholder {
      position: absolute;
      inset: 0;
      display: grid;
      place-items: center;
      color: #fff;
      font-size: 10px;
      font-weight: 800;
      letter-spacing: 1px;
      text-align: center;
      text-transform: uppercase;
      background: rgba(6, 59, 62, 0.6);
    }

    .cancellation-box {
      background-color: #faf8f5;
      border: 1px solid #e2d9c8;
      border-radius: 4px;
      padding: 10px 12px;
      display: flex;
      gap: 10px;
      align-items: flex-start;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }

    .policy-title {
      font-size: 10px;
      font-weight: 700;
      color: #063B3E;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      margin-bottom: 4px;
    }

    .policy-text {
      font-size: 10px;
      color: #555555;
      line-height: 1.4;
    }

    /* FOOTER */
    .voucher-footer {
      background-color: #063B3E;
      height: 32mm;
      margin-top: auto;
      border-top: 2px solid #C89B3C;
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      align-items: center;
      padding: 0 25px;
      color: #ffffff;
      font-size: 12px;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }

    .footer-col {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .footer-col.center {
      border-left: 1px solid rgba(200, 155, 60, 0.3);
      padding-left: 20px;
    }

    .footer-col.right {
      text-align: right;
    }

    .footer-heading {
      font-size: 11px;
      font-weight: 700;
      color: #C89B3C;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-bottom: 2px;
    }

    .footer-line {
      display: flex;
      align-items: center;
      gap: 6px;
      color: #e5e7eb;
      font-size: 12px;
    }

    .compliments-title {
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-size: 22px;
      color: #C89B3C;
    }

    .compliments-sub {
      font-size: 15px;
      font-weight: 600;
      color: #ffffff;
      margin-top: 2px;
    }

    @page {
      size: A4 landscape;
      margin: 0;
    }

    @media print {
      html,
      body {
        margin: 0;
        padding: 0;
      }

      body * {
        visibility: hidden;
      }

      #printVoucher,
      #printVoucher * {
        visibility: visible;
      }

      #printVoucher {
        position: absolute;
        left: 0;
        top: 0;
        width: 297mm;
        height: 210mm;
        page-break-after: avoid;
        page-break-before: avoid;
        page-break-inside: avoid;
      }
    }

    .theme-modern {
      --crimson-primary: #A92D26;
      --crimson-dark: #8B251E;
      --crimson-light: #A92D26;
      --crimson-gold: #C89B3C;
      --crimson-paper: #FFFFFF;
      --crimson-navy: #16373A;
      --crimson-gold-line: rgba(200, 155, 60, 0.34);
      --crimson-gold-soft: rgba(200, 155, 60, 0.10);
      --crimson-gradient: linear-gradient(135deg, #8B251E 0%, #A92D26 52%, #A92D26 100%);
    }

    .theme-modern.voucher-card {
      background: var(--crimson-paper);
    }

    .theme-modern .voucher-header {
      position: relative;
      isolation: isolate;
      overflow: hidden;
      background: var(--crimson-gradient);
      border-bottom: 2px solid var(--crimson-gold);
      box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.10), 0 10px 24px rgba(169, 45, 38, 0.10);
    }

    .theme-modern .voucher-header::before {
      content: '';
      position: absolute;
      inset: 0;
      z-index: 0;
      background:
        radial-gradient(circle at 9% 22%, rgba(255, 255, 255, 0.10) 0 1.5px, transparent 2.5px),
        radial-gradient(circle at 17% 70%, rgba(255, 255, 255, 0.07) 0 1px, transparent 2px),
        radial-gradient(circle at 91% 18%, rgba(231, 98, 91, 0.18) 0 2px, transparent 3px),
        radial-gradient(circle at 80% 82%, rgba(200, 155, 60, 0.12) 0 1px, transparent 2px),
        linear-gradient(125deg, transparent 0 68%, rgba(255, 255, 255, 0.07) 68% 68.5%, transparent 68.5%);
    }

    .theme-modern .voucher-header::after {
      content: '';
      position: absolute;
      z-index: 0;
      top: -72px;
      right: -50px;
      width: 142px;
      height: 142px;
      border: 1px solid rgba(200, 155, 60, 0.36);
      border-radius: 50%;
      box-shadow: 0 0 0 18px rgba(255, 255, 255, 0.04), 0 0 0 34px rgba(200, 155, 60, 0.05), inset 0 0 26px rgba(231, 98, 91, 0.12);
    }

    .theme-modern .voucher-header > * {
      position: relative;
      z-index: 1;
    }

    .theme-modern .voucher-title { color: var(--crimson-gold); }
    .theme-modern .voucher-subtitle { color: rgba(255, 255, 255, 0.94); }
    .theme-modern .header-center { border-right: 1px solid rgba(200, 155, 60, 0.38); }
    .theme-modern .header-right { color: rgba(255, 255, 255, 0.86); }

    .theme-modern .voucher-body {
      background: var(--crimson-paper);
    }

    .theme-modern .section-box {
      background-color: var(--crimson-paper);
      border: 1px solid var(--crimson-gold-line);
    }

    .theme-modern .section-title { color: var(--crimson-navy); }
    .theme-modern .policy-title { color: var(--crimson-navy); }

    .theme-modern .grid-card,
    .theme-modern .cancellation-box {
      background-color: #FFFFFF;
      border: 1px solid var(--crimson-gold-line);
    }

    .theme-modern .card-icon {
      color: var(--crimson-gold);
      background: var(--crimson-gold-soft);
    }

    .theme-modern .card-label { color: var(--crimson-gold); }
    .theme-modern .card-value {
      color: var(--crimson-navy);
      border-bottom: 0;
      box-shadow: inset 0 -1px 0 rgba(200, 155, 60, 0.20);
    }

    .theme-modern .hotel-name { color: var(--crimson-navy); }
    .theme-modern .hotel-sub-location { color: var(--crimson-gold); }
    .theme-modern .hotel-image-container { border-color: rgba(200, 155, 60, 0.56); }
    .theme-modern .hotel-image-placeholder {
      color: var(--crimson-navy);
      background: rgba(200, 155, 60, 0.10);
    }

    .theme-modern .voucher-footer {
      position: relative;
      isolation: isolate;
      overflow: hidden;
      background: var(--crimson-gradient);
      border-top: 2px solid var(--crimson-gold);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.10);
    }

    .theme-modern .voucher-footer::before {
      content: '';
      position: absolute;
      inset: 0;
      z-index: 0;
      background:
        radial-gradient(circle at 12% 70%, rgba(255, 255, 255, 0.08) 0 1.5px, transparent 2.5px),
        radial-gradient(circle at 88% 28%, rgba(231, 98, 91, 0.16) 0 2px, transparent 3px),
        linear-gradient(115deg, transparent 0 72%, rgba(255, 255, 255, 0.06) 72% 72.5%, transparent 72.5%);
    }

    .theme-modern .voucher-footer > * {
      position: relative;
      z-index: 1;
    }

    .theme-modern .footer-heading { color: var(--crimson-gold); }
    .theme-modern .footer-line { color: rgba(255, 255, 255, 0.92); }
    .theme-modern .compliments-title { color: var(--crimson-gold); }
    .theme-modern .compliments-sub { color: rgba(255, 255, 255, 0.92); }

    /* THEME: PREMIUM - Dark red with white body */
    .theme-premium {
      --premium-primary: #B5322B;
      --premium-dark: #8B251E;
      --premium-light: #D95147;
      --premium-gold: #D4AF37;
      --premium-paper: #FFFFFF;
      --premium-navy: #1C1917;
      --premium-gold-line: rgba(212, 175, 55, 0.34);
      --premium-gold-soft: rgba(212, 175, 55, 0.10);
      --premium-gradient: linear-gradient(135deg, #8B251E 0%, #B5322B 52%, #D95147 100%);
    }

    .theme-premium.voucher-card {
      background: var(--premium-paper);
    }

    .theme-premium .voucher-header {
      position: relative;
      isolation: isolate;
      overflow: hidden;
      background: var(--premium-gradient);
      border-bottom: 2px solid var(--premium-gold);
      box-shadow: inset 0 -1px 0 rgba(255, 255, 255, 0.10), 0 10px 24px rgba(139, 37, 30, 0.10);
    }

    .theme-premium .voucher-header::before {
      content: '';
      position: absolute;
      inset: 0;
      z-index: 0;
      background:
        radial-gradient(circle at 9% 22%, rgba(255, 255, 255, 0.10) 0 1.5px, transparent 2.5px),
        radial-gradient(circle at 17% 70%, rgba(255, 255, 255, 0.07) 0 1px, transparent 2px),
        radial-gradient(circle at 91% 18%, rgba(217, 81, 71, 0.18) 0 2px, transparent 3px),
        radial-gradient(circle at 80% 82%, rgba(212, 175, 55, 0.12) 0 1px, transparent 2px),
        linear-gradient(125deg, transparent 0 68%, rgba(255, 255, 255, 0.07) 68% 68.5%, transparent 68.5%);
    }

    .theme-premium .voucher-header::after {
      content: '';
      position: absolute;
      z-index: 0;
      top: -72px;
      right: -50px;
      width: 142px;
      height: 142px;
      border: 1px solid rgba(212, 175, 55, 0.36);
      border-radius: 50%;
      box-shadow: 0 0 0 18px rgba(255, 255, 255, 0.04), 0 0 0 34px rgba(212, 175, 55, 0.05), inset 0 0 26px rgba(217, 81, 71, 0.12);
    }

    .theme-premium .voucher-header > * {
      position: relative;
      z-index: 1;
    }

    .theme-premium .voucher-title { color: var(--premium-gold); }
    .theme-premium .voucher-subtitle { color: rgba(255, 255, 255, 0.94); }
    .theme-premium .header-center { border-right: 1px solid rgba(212, 175, 55, 0.38); }
    .theme-premium .header-right { color: rgba(255, 255, 255, 0.86); }

    .theme-premium .voucher-body {
      background: var(--premium-paper);
    }

    .theme-premium .section-box {
      background-color: var(--premium-paper);
      border: 1px solid var(--premium-gold-line);
    }

    .theme-premium .section-title { color: var(--premium-navy); }
    .theme-premium .policy-title { color: var(--premium-navy); }

    .theme-premium .grid-card,
    .theme-premium .cancellation-box {
      background-color: #FFFFFF;
      border: 1px solid var(--premium-gold-line);
    }

    .theme-premium .card-icon {
      color: var(--premium-gold);
      background: var(--premium-gold-soft);
    }

    .theme-premium .card-label { color: var(--premium-gold); }
    .theme-premium .card-value {
      color: var(--premium-navy);
      border-bottom: 0;
      box-shadow: inset 0 -1px 0 rgba(212, 175, 55, 0.20);
    }

    .theme-premium .hotel-name { color: var(--premium-navy); }
    .theme-premium .hotel-sub-location { color: var(--premium-gold); }
    .theme-premium .hotel-image-container { border-color: rgba(212, 175, 55, 0.56); }
    .theme-premium .hotel-image-placeholder {
      color: var(--premium-navy);
      background: rgba(212, 175, 55, 0.10);
    }

    .theme-premium .voucher-footer {
      position: relative;
      isolation: isolate;
      overflow: hidden;
      background: var(--premium-gradient);
      border-top: 2px solid var(--premium-gold);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.10);
    }

    .theme-premium .voucher-footer::before {
      content: '';
      position: absolute;
      inset: 0;
      z-index: 0;
      background:
        radial-gradient(circle at 12% 70%, rgba(255, 255, 255, 0.08) 0 1.5px, transparent 2.5px),
        radial-gradient(circle at 88% 28%, rgba(217, 81, 71, 0.16) 0 2px, transparent 3px),
        linear-gradient(115deg, transparent 0 72%, rgba(255, 255, 255, 0.06) 72% 72.5%, transparent 72.5%);
    }

    .theme-premium .voucher-footer > * {
      position: relative;
      z-index: 1;
    }

    .theme-premium .footer-heading { color: var(--premium-gold); }
    .theme-premium .footer-line { color: rgba(255, 255, 255, 0.92); }
    .theme-premium .compliments-title { color: var(--premium-gold); }
    .theme-premium .compliments-sub { color: rgba(255, 255, 255, 0.92); }

    /* THEME: CLASSIC - preserve green cancellation icon (inline style removed for themeable color) */
    .theme-classic .cancellation-box .card-icon { color: #063B3E; }
  </style>

<div class="voucher-card theme-{{ $preview ? 'classic' : (($theme = $voucher?->voucher_theme ?? $settings?->voucher_theme ?? 'classic') === 'alma_premium' ? 'premium' : $theme) }} {{ $preview ? 'voucher-preview' : 'voucher-final' }} {{ $photoDisabled ? 'voucher-photo-disabled' : '' }}" id="printVoucher">
  
  <!-- HEADER -->
  <div class="voucher-header">
    <div class="brand-logo">
      <div class="logo-text-group">
        <span class="logo-main">{{ $settings->company_name }}</span>
        <span class="logo-sub">{{ $settings->tagline }}</span>
      </div>
    </div>
    <div class="header-center">
      <div class="voucher-title">CONFIRMATION VOUCHER</div>
      <div class="voucher-subtitle">We Always Care</div>
    </div>
    <div class="header-right">
      Alma Travel<br>
      Premium Hotels<br>
      Exclusive Experiences
    </div>
  </div>

  <!-- BODY -->
  <div class="voucher-body">
    
    <!-- LEFT: BOOKING DETAILS -->
    <div class="section-box">
      <div class="section-title">Booking Details</div>
      <div class="details-grid">
        
        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Guest Name</span>
            <span class="card-value" id="previewGuest">{{ $value('guest_name', 'Guest name') }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V9h10v2z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Confirmation Number</span>
            <span class="card-value" id="previewReference">{{ $voucher->confirmation_reference ?? 'HTL-2026-089742' }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Check-In</span>
            <span class="card-value" id="previewCheckIn">{{ $formattedCheckIn }} <span style="font-weight: normal; font-size: 9px; color: #666;">(From {{ $checkInTime }})</span></span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Check-Out</span>
            <span class="card-value" id="previewCheckOut">{{ $formattedCheckOut }} <span style="font-weight: normal; font-size: 9px; color: #666;">(Until {{ $checkOutTime }})</span></span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M12.3 2c.43 0 .85.05 1.28.14a9.92 9.92 0 0 0-4.8 9.36c.22 5.25 4.5 9.4 9.77 9.47.4 0 .8-.03 1.19-.08A10.012 10.012 0 0 1 2 12C2 6.5 6.47 2 12.3 2z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Nights</span>
            <span class="card-value" id="previewNightsPreview">{{ $nights }} {{ $nights == 1 ? 'Night' : 'Nights' }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.55 3.89 3.57 4.23V22h2.86v-8.77C11.45 12.89 13 11.12 13 9V2h-2v7zm8-7h-2c-1.1 0-2 .9-2 2v8h2v10h2V2z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Meal Plan</span>
            <span class="card-value" id="previewMeal">{{ $voucher->meal_plan ?? 'Breakfast' }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Occupancy</span>
            <span class="card-value" id="previewOccupancy">{{ $occupancy }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V5H1v15h2v-3h18v3h2v-9c0-2.21-1.79-4-4-4z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Room Type</span>
            <span class="card-value" id="previewRoom">{{ $voucher->room_type ?? 'Deluxe Room (King Bed)' }}</span>
          </div>
        </div>

        <div class="grid-card full-width">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Special Requests</span>
            <span class="card-value" id="previewSpecialRequests" style="font-size: 9.5px;">{{ $specialRequests }}</span>
          </div>
        </div>

      </div>
    </div>

    <!-- RIGHT: HOTEL SHOWCASE -->
    <div class="hotel-showcase">
      <div class="hotel-header-flex">
        <div>
          <div class="hotel-name" id="previewHotelTitle">{{ $hotelName }}</div>
          @if($preview || $hotelLocationSub)
          <div class="hotel-sub-location" id="previewHotelLocation">{{ $hotelLocationSub }}</div>
          @endif
          <div class="hotel-contact-info">
            📍 <span id="previewHotelAddress">{{ $hotelAddress }}</span><br>
            📞 <span id="previewHotelPhone">{{ $hotelPhone }}</span>
          </div>
        </div>
        @if($logoSource)
        <img src="{{ $logoSource }}" alt="Hotel Logo" class="hotel-logo-img">
        @endif
      </div>

      <div class="hotel-image-container">
        <img id="previewHotelPhoto" src="{{ $hasHotelPhoto ? $hotelPhotoUrl : '' }}" alt="Hotel Preview" class="{{ ! $hasHotelPhoto ? 'd-none' : '' }}">
        <div id="previewPhotoPlaceholder" class="hotel-image-placeholder {{ $hasHotelPhoto ? 'd-none' : '' }}">Hotel image not included</div>
      </div>

      <div class="cancellation-box">
        <div class="card-icon">
          <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-5.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
        </div>
        <div>
          <div class="policy-title">Cancellation Policy</div>
            <div class="policy-text" id="previewPolicy">
            {{ $policy }}
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- FOOTER -->
  <div class="voucher-footer">
    <div class="footer-col">
      <div class="footer-heading">Alma Travel Contact Details</div>
      <div class="footer-line">📞 {{ $settings->phone ?? '+966 920022050' }}</div>
      <div class="footer-line">✉️ {{ $settings->email ?? 'info@almatravel.co' }}</div>
      <div class="footer-line">🌐 {{ $settings->website ?? 'www.almatravel.co' }}</div>
      <div class="footer-line">📍 {{ $settings->address ?? 'Riyadh, Saudi Arabia' }}</div>
    </div>

    <div class="footer-col center">
      <div class="footer-heading">Booking Consultant Details</div>
      @if($preview)
      <div class="footer-line">👤 <span id="previewConsultantName">Alma Travel Team</span></div>
      <div class="footer-line">✉️ <span id="previewConsultantEmail">Booking support</span></div>
      <div class="footer-line">📞 <span id="previewConsultantPhone">{{ $settings->phone ?? '+966 920022050' }}</span></div>
      @elseif($employee)
      <div class="footer-line">👤 <span id="previewConsultantName">{{ $employee->name }}</span></div>
      <div class="footer-line">✉️ <span id="previewConsultantEmail">{{ $employee->email }}</span></div>
      <div class="footer-line">📞 <span id="previewConsultantPhone">{{ $employee->phone }}</span></div>
      @else
      <div class="footer-line">👤 <span id="previewConsultantName">Alma Travel Team</span></div>
      <div class="footer-line">✉️ <span id="previewConsultantEmail">Booking support</span></div>
      <div class="footer-line">📞 <span id="previewConsultantPhone">{{ $settings->phone ?? '+966 920022050' }}</span></div>
      @endif
    </div>

    <div class="footer-col right">
      <div class="compliments-title">With Compliments</div>
      <div class="compliments-sub">Alma Travel</div>
    </div>
  </div>

</div>
