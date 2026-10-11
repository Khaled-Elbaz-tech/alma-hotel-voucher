@php
    $preview = (bool) ($preview ?? false);
    $logoData = $logoData ?? null;
    $hotelPhotoData = $hotelPhotoData ?? null;

    $value = fn (string $key, string $fallback = '') => $preview ? $fallback : data_get($voucher, $key, $fallback);
    $formattedDate = $preview ? 'Select date' : $voucher->check_in_date->format('d M Y');
    $serviceTime = $preview ? '10:00' : ($voucher->check_in_time ?? '10:00');
    $companyName = $preview ? 'Agent name' : ($voucher->company_name ?? '—');
    $companyAddress = $preview ? 'Agent company' : ($voucher->company_address ?? '—');
    $companyPhone = $preview ? 'Agent phone' : ($voucher->company_phone ?? '—');
    $companyEmail = $preview ? 'agent@company.com' : ($voucher->company_email ?? '');
    $meetingPoint = $preview ? 'Meeting point' : ($voucher->meeting_point ?? '—');
    $terminal = $preview ? '—' : ($voucher->terminal ?? '—');
    $flightNumber = $preview ? '—' : ($voucher->flight_number ?: '—');
    $flightArrivalTime = $preview ? '—' : ($voucher->flight_arrival_time ?? '—');
    $serviceType = $preview ? 'Select service type' : ($voucher->vehicle_type ?? '—');
    $passengers = $preview ? '1' : ($voucher->passengers ?? '—');
    $vipLounge = $preview ? false : (bool) $voucher->vip_lounge_access;
    $fastTrack = $preview ? false : (bool) $voucher->fast_track;
    $porterService = $preview ? false : (bool) $voucher->porter_service;
    $specialRequests = $preview ? 'Special requests' : ($voucher->special_requests ?: '—');
    $employee = $preview ? null : $voucher->employee;
    $logoSource = $logoData ?? ($settings->logoUrl() ?? null);
    $hasPhoto = ! $preview && $voucher->include_hotel_photo && ($voucher->hotel_photo_path || $hotelPhotoData);
    $photoDisabled = ! $hasPhoto;
    $voucherNumber = $preview ? 'ALM-VIP-20260917-0001' : $voucher->voucher_number;
    $voucherDate = $preview ? now()->format('d M Y') : $voucher->created_at->format('d M Y');
    $servicePhotoUrl = $hotelPhotoData ?? ($voucher ? ($voucher->hotelPhotoUrl() ?? 'https://images.unsplash.com/photo-1565728353228-9c1d43d3e3d3?auto=format&fit=crop&w=1000&q=80') : 'https://images.unsplash.com/photo-1565728353228-9c1d43d3e3d3?auto=format&fit=crop&w=1000&q=80');
    $heroImage = asset('images/vouchers/vip-meet-assist-hero.jpg');

    $amenitiesList = collect([]);
    if ($vipLounge) { $amenitiesList->push('VIP Lounge Access'); }
    if ($fastTrack) { $amenitiesList->push('Fast Track'); }
    if ($porterService) { $amenitiesList->push('Porter Service'); }
    $amenitiesText = $preview ? 'VIP Lounge Access, Fast Track, Porter Service' : ($amenitiesList->isNotEmpty() ? $amenitiesList->join(', ') : 'N/A');
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
    .company-name,
    .voucher-title {
      font-family: 'Georgia', serif !important;
      font-size: 17px !important;
      font-weight: 700;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }

    /* RIGHT SECTION - COMPANY SHOWCASE */
    .company-showcase {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .company-header-flex {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    .company-name {
      font-family: 'Georgia', serif !important;
      font-size: 23px;
      font-weight: 700;
      color: #063B3E;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }

    .company-sub-location {
      font-size: 10px;
      letter-spacing: 1px;
      color: #C89B3C;
      font-weight: 700;
      margin-top: 2px;
      text-transform: uppercase;
    }

    .company-contact-info {
      font-size: 10px;
      color: #555555;
      margin-top: 6px;
      line-height: 1.4;
    }

    .company-logo-img {
      max-width: 90px;
      max-height: 40px;
      object-fit: contain;
    }

    .company-image-container {
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

    .company-image-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .company-image-placeholder {
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

    .theme-modern .company-name { color: var(--crimson-navy); }
    .theme-modern .company-sub-location { color: var(--crimson-gold); }
    .theme-modern .company-image-container { border-color: rgba(200, 155, 60, 0.56); }
    .theme-modern .company-image-placeholder {
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

    .theme-premium .company-name { color: var(--premium-navy); }
    .theme-premium .company-sub-location { color: var(--premium-gold); }
    .theme-premium .company-image-container { border-color: rgba(212, 175, 55, 0.56); }
    .theme-premium .company-image-placeholder {
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
      <div class="voucher-title">VIP MEET &amp; ASSIST VOUCHER</div>
      <div class="voucher-subtitle">Your Journey, Our Commitment</div>
    </div>
    <div class="header-right">
      Alma Travel<br>
      Airport Transfers<br>
      Chauffeur Services
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
            <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.33-8 4v2h16v-2c0-2.67-5.33-4-8-4z"/></svg>
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
            <span class="card-value" id="previewReference">{{ $value('confirmation_reference', 'Reference') }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Service Date</span>
            <span class="card-value" id="previewServiceDate">{{ $formattedDate }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M12 7V3L8 9h3v8H8l4 6 4-6h-3z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Service Time</span>
            <span class="card-value" id="previewServiceTime">{{ $serviceTime }}</span>
          </div>
        </div>

        <div class="grid-card full-width">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Meeting Point</span>
            <span class="card-value" id="previewMeetingPoint">{{ $meetingPoint }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M21.35 11h-1.63c-.32-.95-.79-.89-1.15-1.39-.42-.57-1.12-1.86-1.59-3.04-.22-.56-.74-1.24-1.3--1.67-1.06-.94-2.23-1.74-3.47-2.15C12.16 2.38 11.13 2 12 2c3.86 0 7 3.14 7 7v4.28c.57.44 1.25 1.14 1.69 1.95.27.48.89 1.51 1.11 2.07-.11.05-.23.07-.38.07h-.69c-.88 0-1.95-.79-2.33-1.58.38-.5 1-1.22 1.42-1.39-.15-.45-.37-.86-.63-1.25.61-.38 1.02-1 1.15-1.61-.51-.34-1.09-.57-1.7-.57h-.19l-.82.14c-.38.07-1.15.44-1.56.77.23.39.35.92.22 1.5-.16 2.82-2.08 4.58-4.52 4.58-1.05 0-1.98-.32-2.77-.86 1.69 2.02 4.41 3.29 7.32 3.29 1.98 0 3.88-.37 5.66-1.07-.44 2.15-2.14 3.94-4.33 4.3h-.77c-2.41-.26-4.42-1.58-5.6-3.41-.36-.54-1.82-2.33-2.5-3.25.62-.38 1.25-.94 1.77-1.66l.85-.96c.37-.42.72-.86 1-1.32-.68-.48-1.28-1.13-1.76-1.91-.68-.24-1.37-.35-2.06-.35-.69 0-1.38.05-2.05.3.86-.63 1.89-1.04 2.95-1.04 1.17 0 2.24.36 3.09 1.09l.36.27.35-.24c1.29-.82 2.26-1.93 2.81-3.19l.04-.08c.67.04 1.35.19 1.97.59-.25-.71-.6-1.37-1.06-1.94.64-.4 1.4-.61 2.19-.61.79 0 1.55.26 2.18.77l.49-.25c1.21-.86 2.67-1.29 4.12-1.29.04.67.06 1.35.06 2.03C20.41 4.74 16.88 2 12 2z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Flight Number</span>
            <span class="card-value" id="previewFlightNumber">{{ $flightNumber }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Arrival Time</span>
            <span class="card-value" id="previewFlightArrivalTime">{{ $flightArrivalTime }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Terminal</span>
            <span class="card-value" id="previewTerminal">{{ $terminal }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.55 3.89 3.57 4.23V22h2.86v-8.77C11.45 12.89 13 11.12 13 9V2h-2v7zm8-7h-2c-1.1 0-2 .9-2 2v8h2v10h2V2z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Service Type</span>
            <span class="card-value" id="previewServiceType">{{ $value('vehicle_type', 'Select option') }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V5H1v15h2v-3h18v3h2v-9c0-2.21-1.79-4-4-4z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Passengers</span>
            <span class="card-value" id="previewPassengers">{{ $value('passengers', '1') }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M12 7V3L8 9h3v8H8l4 6 4-6h-3z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">VIP Lounge Access</span>
            <span class="card-value" id="previewVipLounge">{{ $value('vip_lounge_access') ? 'Yes' : 'No' }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Fast Track</span>
            <span class="card-value" id="previewFastTrack">{{ $value('fast_track') ? 'Yes' : 'No' }}</span>
          </div>
        </div>

        <div class="grid-card">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm0 4l6 6h-6V6zm6 12c-.55 0-1-.45-1-1v-5h2v5c0 .55-.45 1-1 1z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Porter Service</span>
            <span class="card-value" id="previewPorter">{{ $value('porter_service') ? 'Yes' : 'No' }}</span>
          </div>
        </div>

        <div class="grid-card full-width">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.33-8 4v2h16v-2c0-2.67-5.33-4-8-4z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Special Requests</span>
            <span class="card-value" id="previewSpecialRequests" style="font-size: 9.5px;">{{ $specialRequests }}</span>
          </div>
        </div>

      </div>
    </div>

    <!-- RIGHT: COMPANY SHOWCASE -->
    <div class="company-showcase">
      <div class="company-header-flex">
        <div>
          <div class="company-name" id="previewCompanyName">{{ $companyName }}</div>
          <div class="company-contact-info">
            📍 <span id="previewMeetingPoint">{{ $meetingPoint }}</span><br>
            📞 <span id="previewCompanyPhone">{{ $companyPhone }}</span><br>
            @if ($value('company_email'))
            ✉️ <span id="previewCompanyEmail">{{ $value('company_email', '') }}</span><br>
            @endif
          </div>
        </div>
        @if ($logoSource)
        <img src="{{ $logoSource }}" alt="Company Logo" class="company-logo-img">
        @endif
      </div>

      <div class="company-image-container">
        <img id="previewServicePhoto" src="{{ $hasPhoto ? $servicePhotoUrl : $heroImage }}" alt="Service Preview" data-hero-image="{{ $heroImage }}">
        <div id="previewPhotoPlaceholder" class="company-image-placeholder d-none">No service image included</div>
      </div>

      <div class="cancellation-box">
        <div class="card-icon">
          <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-5.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
        </div>
        <div>
          <div class="policy-title">VIP Amenities</div>
          <div class="policy-text" id="previewAmenities">{{ $amenitiesText }}</div>
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
