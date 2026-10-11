@php
    $preview = (bool) ($preview ?? false);
    $logoData = $logoData ?? null;
    $hotelPhotoData = $hotelPhotoData ?? null;
    $value = fn (string $key, string $fallback = '') => $preview ? $fallback : data_get($voucher, $key, $fallback);
    $formattedCheckIn = $preview ? 'Select date' : $voucher->check_in_date->format('d M Y');
    $formattedCheckOut = $preview ? 'Select date' : $voucher->check_out_date->format('d M Y');
    $pickupTime = $preview ? '10:00' : ($voucher->pickup_time ?? $voucher->check_in_time ?? '10:00');
    $dropoffTime = $preview ? '12:00' : ($voucher->dropoff_time ?? $voucher->check_out_time ?? '12:00');
    $days = $preview ? 0 : $voucher->nights_count;
    $companyName = $preview ? 'Rental company' : ($voucher->rental_company ?? '—');
    $companyAddress = $preview ? 'Company address' : ($voucher->company_address ?? '—');
    $companyPhone = $preview ? 'Company phone' : ($voucher->company_phone ?? '—');
    $companyEmail = $preview ? '' : ($voucher->company_email ?? '');
    $pickupLocation = $preview ? 'Pickup location' : ($voucher->pickup_location ?? '—');
    $dropoffLocation = $preview ? 'Drop-off location' : ($voucher->dropoff_location ?? '—');
    $vehicleType = $preview ? 'Select type' : ($voucher->vehicle_type ?? '—');
    $vehicleModel = $preview ? 'Vehicle model' : ($voucher->vehicle_model ?? '—');
    $insuranceType = $preview ? 'Select insurance' : ($voucher->insurance_type ?? '—');
    $supplierReference = $preview ? 'Supplier reference' : ($voucher->supplier_reference ?? '—');
    $importantNotes = $preview ? 'Add important notes here' : ($voucher->important_notes ?: '—');
    $pickupTerminal = $preview ? '' : ($voucher->pickup_terminal ?? '');
    $dropoffTerminal = $preview ? '' : ($voucher->dropoff_terminal ?? '');
    $bookingNumber = $preview ? '—' : ($voucher->booking_number ?? '—');
    $contactNumber = $preview ? '—' : ($voucher->contact_number ?? '—');
    $idPassport = $preview ? '—' : ($voucher->id_passport_number ?? '—');
    $employee = $preview ? null : $voucher->employee;
    $logoSource = $logoData ?? ($settings->logoUrl() ?? null);
    $hasCarPhoto = ! $preview && $voucher->include_hotel_photo && ($voucher->hotel_photo_path || $hotelPhotoData);
    $photoDisabled = ! $hasCarPhoto;
    $voucherNumber = $preview ? 'ALM-CR-20260917-0001' : $voucher->voucher_number;
    $voucherDate = $preview ? now()->format('d M Y') : $voucher->created_at->format('d M Y');
    $carPhotoUrl = $hotelPhotoData ?? ($voucher ? ($voucher->hotelPhotoUrl() ?? 'https://images.unsplash.com/photo-1632476800512-2b2c8ea5f000?auto=format&fit=crop&w=1000&q=80') : 'https://images.unsplash.com/photo-1632476800512-2b2c8ea5f000?auto=format&fit=crop&w=1000&q=80');
    $heroImage = asset('images/vouchers/car-rental-hero.jpg');
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
      font-family: 'Playfair Display', serif;
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
    /* LEFT SECTION - DETAILS */
    .section-box {
      border: 1px solid #e2d9c8;
      border-radius: 4px;
      padding: 12px;
      background-color: #faf8f5;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
      margin-bottom: 10px;
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
      font-family: 'Playfair Display', serif !important;
      font-size: 17px !important;
      font-weight: 700;
      color: #111111;
      margin-top: 2px;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }
    .card-value.sub-text {
      font-size: 10px;
      color: #666;
      font-weight: 400;
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
      font-family: 'Playfair Display', serif !important;
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
      height: 170px;
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
      html, body { margin: 0; padding: 0; }
      body * { visibility: hidden; }
      #printVoucher, #printVoucher * { visibility: visible; }
      #printVoucher {
        position: absolute;
        left: 0; top: 0;
        width: 297mm;
        height: 210mm;
        page-break-after: avoid;
        page-break-before: avoid;
        page-break-inside: avoid;
      }
    }
    /* THEME: MODERN */
    .theme-modern {
      --theme-primary: #A92D26;
      --theme-dark: #8B251E;
      --theme-accent: #C89B3C;
      --theme-paper: #FFFFFF;
      --theme-navy: #16373A;
    }
    .theme-modern.voucher-card { background: var(--theme-paper); }
    .theme-modern .voucher-header {
      position: relative;
      isolation: isolate;
      overflow: hidden;
      background: linear-gradient(135deg, #8B251E 0%, #A92D26 52%, #A92D26 100%);
      border-bottom: 2px solid var(--theme-accent);
      box-shadow: inset 0 -1px 0 rgba(255,255,255,0.10), 0 10px 24px rgba(169,45,38,0.10);
    }
    .theme-modern .voucher-header::before {
      content: '';
      position: absolute;
      inset: 0;
      z-index: 0;
      background:
        radial-gradient(circle at 9% 22%, rgba(255,255,255,0.10) 0 1.5px, transparent 2.5px),
        radial-gradient(circle at 17% 70%, rgba(255,255,255,0.07) 0 1px, transparent 2px),
        radial-gradient(circle at 91% 18%, rgba(231,98,91,0.18) 0 2px, transparent 3px),
        radial-gradient(circle at 80% 82%, rgba(200,155,60,0.12) 0 1px, transparent 2px),
        linear-gradient(125deg, transparent 0 68%, rgba(255,255,255,0.07) 68% 68.5%, transparent 68.5%);
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
      box-shadow: 0 0 0 18px rgba(255,255,255,0.04), 0 0 0 34px rgba(200,155,60,0.05), inset 0 0 26px rgba(231,98,91,0.12);
    }
    .theme-modern .voucher-header > * { position: relative; z-index: 1; }
    .theme-modern .voucher-title { color: var(--theme-accent); }
    .theme-modern .voucher-subtitle { color: rgba(255,255,255,0.94); }
    .theme-modern .header-center { border-right: 1px solid rgba(200, 155, 60, 0.38); }
    .theme-modern .header-right { color: rgba(255,255,255,0.86); }
    .theme-modern .voucher-body { background: var(--theme-paper); }
    .theme-modern .section-box { background-color: var(--theme-paper); border: 1px solid rgba(200,155,60,0.34); }
    .theme-modern .section-title { color: var(--theme-navy); }
    .theme-modern .card-icon { color: var(--theme-accent); background: rgba(200,155,60,0.10); }
    .theme-modern .card-label { color: var(--theme-accent); }
    .theme-modern .card-value { color: var(--theme-navy); }
    .theme-modern .company-name { color: var(--theme-navy); }
    .theme-modern .company-sub-location { color: var(--theme-accent); }
    .theme-modern .company-image-container { border-color: rgba(200,155,60,0.56); }
    .theme-modern .company-image-placeholder { color: var(--theme-navy); background: rgba(200,155,60,0.10); }
    .theme-modern .voucher-footer {
      position: relative;
      isolation: isolate;
      overflow: hidden;
      background: linear-gradient(135deg, #8B251E 0%, #A92D26 52%, #A92D26 100%);
      border-top: 2px solid var(--theme-accent);
      box-shadow: inset 0 1px 0 rgba(255,255,255,0.10);
    }
    .theme-modern .voucher-footer::before {
      content: '';
      position: absolute;
      inset: 0;
      z-index: 0;
      background:
        radial-gradient(circle at 12% 70%, rgba(255,255,255,0.08) 0 1.5px, transparent 2.5px),
        radial-gradient(circle at 88% 28%, rgba(231,98,91,0.16) 0 2px, transparent 3px),
        linear-gradient(115deg, transparent 0 72%, rgba(255,255,255,0.06) 72% 72.5%, transparent 72.5%);
    }
    .theme-modern .voucher-footer > * { position: relative; z-index: 1; }
    .theme-modern .footer-heading { color: var(--theme-accent); }
    .theme-modern .footer-line { color: rgba(255,255,255,0.92); }
    .theme-modern .compliments-title { color: var(--theme-accent); }
    .theme-modern .compliments-sub { color: rgba(255,255,255,0.92); }
    /* THEME: PREMIUM */
    .theme-premium {
      --theme-primary: #B5322B;
      --theme-dark: #8B251E;
      --theme-accent: #D4AF37;
      --theme-paper: #FFFFFF;
      --theme-navy: #1C1917;
    }
    .theme-premium.voucher-card { background: var(--theme-paper); }
    .theme-premium .voucher-header {
      position: relative;
      isolation: isolate;
      overflow: hidden;
      background: linear-gradient(135deg, #8B251E 0%, #B5322B 52%, #D95147 100%);
      border-bottom: 2px solid var(--theme-accent);
      box-shadow: inset 0 -1px 0 rgba(255,255,255,0.10), 0 10px 24px rgba(139,37,30,0.10);
    }
    .theme-premium .voucher-header::before {
      content: '';
      position: absolute;
      inset: 0;
      z-index: 0;
      background:
        radial-gradient(circle at 9% 22%, rgba(255,255,255,0.10) 0 1.5px, transparent 2.5px),
        radial-gradient(circle at 17% 70%, rgba(255,255,255,0.07) 0 1px, transparent 2px),
        radial-gradient(circle at 91% 18%, rgba(217,81,71,0.18) 0 2px, transparent 3px),
        radial-gradient(circle at 80% 82%, rgba(212,175,55,0.12) 0 1px, transparent 2px),
        linear-gradient(125deg, transparent 0 68%, rgba(255,255,255,0.07) 68% 68.5%, transparent 68.5%);
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
      box-shadow: 0 0 0 18px rgba(255,255,255,0.04), 0 0 0 34px rgba(212,175,55,0.05), inset 0 0 26px rgba(217,81,71,0.12);
    }
    .theme-premium .voucher-header > * { position: relative; z-index: 1; }
    .theme-premium .voucher-title { color: var(--theme-accent); }
    .theme-premium .voucher-subtitle { color: rgba(255,255,255,0.94); }
    .theme-premium .header-center { border-right: 1px solid rgba(212, 175, 55, 0.38); }
    .theme-premium .header-right { color: rgba(255,255,255,0.86); }
    .theme-premium .voucher-body { background: var(--theme-paper); }
    .theme-premium .section-box { background-color: var(--theme-paper); border: 1px solid rgba(212,175,55,0.34); }
    .theme-premium .section-title { color: var(--theme-navy); }
    .theme-premium .card-icon { color: var(--theme-accent); background: rgba(212,175,55,0.10); }
    .theme-premium .card-label { color: var(--theme-accent); }
    .theme-premium .card-value { color: var(--theme-navy); }
    .theme-premium .company-name { color: var(--theme-navy); }
    .theme-premium .company-sub-location { color: var(--theme-accent); }
    .theme-premium .company-image-container { border-color: rgba(212,175,55,0.56); }
    .theme-premium .company-image-placeholder { color: var(--theme-navy); background: rgba(212,175,55,0.10); }
    .theme-premium .voucher-footer {
      position: relative;
      isolation: isolate;
      overflow: hidden;
      background: linear-gradient(135deg, #8B251E 0%, #B5322B 52%, #D95147 100%);
      border-top: 2px solid var(--theme-accent);
      box-shadow: inset 0 1px 0 rgba(255,255,255,0.10);
    }
    .theme-premium .voucher-footer::before {
      content: '';
      position: absolute;
      inset: 0;
      z-index: 0;
      background:
        radial-gradient(circle at 12% 70%, rgba(255,255,255,0.08) 0 1.5px, transparent 2.5px),
        radial-gradient(circle at 88% 28%, rgba(217,81,71,0.16) 0 2px, transparent 3px),
        linear-gradient(115deg, transparent 0 72%, rgba(255,255,255,0.06) 72% 72.5%, transparent 72.5%);
    }
    .theme-premium .voucher-footer > * { position: relative; z-index: 1; }
    .theme-premium .footer-heading { color: var(--theme-accent); }
    .theme-premium .footer-line { color: rgba(255,255,255,0.92); }
    .theme-premium .compliments-title { color: var(--theme-accent); }
    .theme-premium .compliments-sub { color: rgba(255,255,255,0.92); }
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
      <div class="voucher-title">CAR RENTAL VOUCHER</div>
      <div class="voucher-subtitle">Your Journey, Our Commitment</div>
    </div>
    <div class="header-right">
      Alma Travel<br>
      Car Rental Services<br>
      Airport Transfers
    </div>
  </div>
  <!-- BODY -->
  <div class="voucher-body">
    <div class="voucher-details">
      <!-- Booking Details -->
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
              <svg viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Booking Number</span>
              <span class="card-value" id="previewBookingNumber">{{ $bookingNumber }}</span>
            </div>
          </div>
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M6.62 10.79a15.05 15.05 0 0 1 6.4-6.4 1 1 0 0 0-1.24-1.55 17.04 17.04 0 0 0-7.37 9.36c.22 5.25 4.5 9.4 9.77 9.47.4 0 .8-.03 1.19-.08A10.012 10.012 0 0 1 2 12C2 6.5 6.47 2 12.3 2z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Confirmation</span>
              <span class="card-value" id="previewReference">{{ $value('confirmation_reference', '—') }}</span>
            </div>
          </div>
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.33-8 4v2h16v-2c0-2.67-5.33-4-8-4z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">ID / Passport</span>
              <span class="card-value" id="previewIdPassport">{{ $idPassport }}</span>
            </div>
          </div>
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M6.62 10.79a15.05 15.05 0 0 1 6.4-6.4 1 1 0 0 0-1.24-1.55 17.04 17.04 0 0 0-7.37 9.36c.22 5.25 4.5 9.4 9.77 9.47.4 0 .8-.03 1.19-.08A10.012 10.012 0 0 1 2 12C2 6.5 6.47 2 12.3 2z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Contact Number</span>
              <span class="card-value" id="previewContactNumber">{{ $contactNumber }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Pickup Details -->
      <div class="section-box">
        <div class="section-title">Pickup Details</div>
        <div class="details-grid">
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Date &amp; Time</span>
              <span class="card-value" id="previewCheckIn">{{ $formattedCheckIn }} <span class="sub-text">(From {{ $pickupTime }})</span></span>
            </div>
          </div>
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 9 5 9s7 7 7 7 7-7 7-7-3.13-7-7-7zm0 2.92c1.38 0 2.5 1.12 2.5 2.5s-1.12 2.5-2.5 2.5-2.5-1.12-2.5-2.5 1.12-2.5 2.5-2.5z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Pickup Location</span>
              <span class="card-value" id="previewPickupLocation">{{ $pickupLocation }}</span>
            </div>
          </div>
          @if ($pickupTerminal)
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M20 3H4c-1.1 0-2 .9-2 2v6h2V5h16v6h2V5c0-1.1-.9-2-2-2zm0 8.84c.56.45 1 1.11 1 1.9v7.26c0 1.66-1.34 3-3 3H8c-1.66 0-3-1.34-3-3V13.74c0-.79.44-1.45 1-1.9V9c0-1.1.89-2 2-2h12c1.1 0 2 .9 2 2v2.84z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Pickup Terminal</span>
              <span class="card-value" id="previewPickupTerminal">{{ $pickupTerminal }}</span>
            </div>
          </div>
          @else
          <div class="grid-card d-none" id="previewPickupTerminalCard">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M20 3H4c-1.1 0-2 .9-2 2v6h2V5h16v6h2V5c0-1.1-.9-2-2-2zm0 8.84c.56.45 1 1.11 1 1.9v7.26c0 1.66-1.34 3-3 3H8c-1.66 0-3-1.34-3-3V13.74c0-.79.44-1.45 1-1.9V9c0-1.1.89-2 2-2h12c1.1 0 2 .9 2 2v2.84z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Pickup Terminal</span>
              <span class="card-value" id="previewPickupTerminal"></span>
            </div>
          </div>
          @endif
        </div>
      </div>

      <!-- Dropoff Details -->
      <div class="section-box">
        <div class="section-title">Dropoff Details</div>
        <div class="details-grid">
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Date &amp; Time</span>
              <span class="card-value" id="previewCheckOut">{{ $formattedCheckOut }} <span class="sub-text">(Until {{ $dropoffTime }})</span></span>
            </div>
          </div>
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 9 5 9s7 7 7 7 7-7 7-7-3.13-7-7-7zm0 2.92c1.38 0 2.5 1.12 2.5 2.5s-1.12 2.5-2.5 2.5-2.5-1.12-2.5-2.5 1.12-2.5 2.5-2.5z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Drop-off Location</span>
              <span class="card-value" id="previewDropoffLocation">{{ $dropoffLocation }}</span>
            </div>
          </div>
          @if ($dropoffTerminal)
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M20 3H4c-1.1 0-2 .9-2 2v6h2V5h16v6h2V5c0-1.1-.9-2-2-2zm0 8.84c.56.45 1 1.11 1 1.9v7.26c0 1.66-1.34 3-3 3H8c-1.66 0-3-1.34-3-3V13.74c0-.79.44-1.45 1-1.9V9c0-1.1.89-2 2-2h12c1.1 0 2 .9 2 2v2.84z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Drop-off Terminal</span>
              <span class="card-value" id="previewDropoffTerminal">{{ $dropoffTerminal }}</span>
            </div>
          </div>
          @else
          <div class="grid-card d-none" id="previewDropoffTerminalCard">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M20 3H4c-1.1 0-2 .9-2 2v6h2V5h16v6h2V5c0-1.1-.9-2-2-2zm0 8.84c.56.45 1 1.11 1 1.9v7.26c0 1.66-1.34 3-3 3H8c-1.66 0-3-1.34-3-3V13.74c0-.79.44-1.45 1-1.9V9c0-1.1.89-2 2-2h12c1.1 0 2 .9 2 2v2.84z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Drop-off Terminal</span>
              <span class="card-value" id="previewDropoffTerminal"></span>
            </div>
          </div>
          @endif
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M12 1c-2.67 0-6 1.35-6 4v1H3c-.55 0-1 .45-1 1v9c0 1.56 3.09 2.91 6.94 3.77l-.94 2.88c-.12.36.15.73.52.59.28-.1.56-.21.84-.33-.31-.23-.62-.47-.93-.73C6.72 18.79 7.2 19 8 19V8h8v8c0 .45.12.87.32 1.26-.13.07-.27.14-.4.21.85.31 1.72.51 2.28.51.34 0 .66-.05 1-.14V9c0-.55-.45-1-1-1h-1V8c0-2.65-3.33-4-6-4zm1 5v2H8V8h1V5h2v2h1zm-6 7h2v5H7v-5zm10 5h-2v-5h2v5z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Rental Duration</span>
              <span class="card-value" id="previewNightsPreview">{{ $days }} {{ $days == 1 ? 'Day' : 'Days' }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Vehicle Details -->
      <div class="section-box">
        <div class="section-title">Vehicle Details</div>
        <div class="details-grid">
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M22.78 20H5.17l-1.17-1.17C3.42 18.45 3 17.75 3 17v-7.59c0-1.34.52-2.62 1.43-3.53L6.17 4c.91-.91 2.18-1.43 3.53-1.43h8.26c1.34 0 2.62.52 3.53 1.43l1.57 1.57c.91.91 1.43 2.19 1.43 3.53V17c0 1.66-1.34 3-3 3z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Vehicle Type</span>
              <span class="card-value" id="previewVehicleType">{{ $vehicleType }}</span>
            </div>
          </div>
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M22.78 20H5.17l-1.17-1.17C3.42 18.45 3 17.75 3 17v-7.59c0-1.34.52-2.62 1.43-3.53L6.17 4c.91-.91 2.19-1.43 3.53-1.43h8.26c1.34 0 2.62.52 3.53 1.43l1.57 1.57c.91 .91 1.43 2.19 1.43 3.53V17c0 1.66-1.34 3-3 3z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Vehicle Model / Similar</span>
              <span class="card-value" id="previewVehicleModel">{{ $vehicleModel }}</span>
            </div>
          </div>
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M12 1l-9 4v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-5.45 9-12V5l-9-4zM8 8h2v2H8V8zm6 0h2v2h-2V8z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Insurance Type</span>
              <span class="card-value" id="previewInsuranceType">{{ $insuranceType }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Rental Company -->
      <div class="section-box">
        <div class="section-title">Rental Company</div>
        <div class="details-grid">
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M20 3H4c-1.1 0-2 .9-2 2v6h2V5h16v6h2V5c0-1.1-.9-2-2-2zm0 8.84c.56.45 1 1.11 1 1.9v7.26c0 1.66-1.34 3-3 3H8c-1.66 0-3-1.34-3-3V13.74c0-.79.44-1.45 1-1.9V9c0-1.1.89-2 2-2h12c1.1 0 2 .9 2 2v2.84z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Rental Company</span>
              <span class="card-value" id="previewRentalCompany">{{ $companyName }}</span>
            </div>
          </div>
          <div class="grid-card">
            <div class="card-icon">
              <svg viewBox="0 0 24 24"><path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.55 3.89 3.57 4.23V22h2.86v-8.77C11.45 12.89 13 11.12 13 9V2h-2v7zm8-7h-2c-1.1 0-2 .9-2 2v8h2v10h2V2z"/></svg>
            </div>
            <div class="card-content">
              <span class="card-label">Supplier Reference</span>
              <span class="card-value" id="previewSupplierReference">{{ $supplierReference }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Important Notes -->
      <div class="section-box">
        <div class="section-title">Important Notes</div>
        <div class="grid-card full-width">
          <div class="card-icon">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
          </div>
          <div class="card-content">
            <span class="card-label">Notes</span>
            <span class="card-value small" id="previewImportantNotes">{{ $importantNotes }}</span>
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
            📍 <span id="previewPickupLocation2">{{ $pickupLocation }}</span><br>
            📍 <span id="previewDropoffLocation2">{{ $dropoffLocation }}</span><br>
            📞 <span id="previewCompanyPhone">{{ $companyPhone }}</span><br>
            @if ($companyEmail)
            ✉️ <span id="previewCompanyEmail">{{ $companyEmail }}</span><br>
            @endif
          </div>
        </div>
        @if ($logoSource)
        <img src="{{ $logoSource }}" alt="Company Logo" class="company-logo-img">
        @endif
      </div>
      <div class="company-image-container">
        <img id="previewCarPhoto" src="{{ $hasCarPhoto ? $carPhotoUrl : $heroImage }}" alt="Vehicle Preview" data-hero-image="{{ $heroImage }}">
        <div id="previewPhotoPlaceholder" class="company-image-placeholder d-none">No vehicle image included</div>
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
