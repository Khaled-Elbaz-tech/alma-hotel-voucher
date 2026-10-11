const iconPaths = {
    user: <><circle cx="12" cy="8" r="4" /><path d="M5 21a7 7 0 0 1 14 0" /></>,
    document: <><path d="M6 3h9l5 5v13H6Z" /><path d="M14 3v6h6M9 13h7M9 17h5" /></>,
    calendarIn: <><rect x="4" y="5" width="16" height="16" rx="1" /><path d="M4 9h16M8 3v4m8-4v4M12 12v5m0 0 2-2m-2 2-2-2" /></>,
    calendarOut: <><rect x="4" y="5" width="16" height="16" rx="1" /><path d="M4 9h16M8 3v4m8-4v4M12 17v-5m0 0-2 2m2 2 2-2" /></>,
    moon: <path d="M19 15A8 8 0 1 1 9 5a6.5 6.5 0 0 0 10 10Z" />,
    utensils: <><path d="M7 3v7M4 3v5a3 3 0 0 0 6 0V3M7 10v11M17 3c-2 0-3 2.5-3 5v3h3v10M17 3v18" /></>,
    bed: <><path d="M3 18v-8m0 5h18v3M3 15h7v-3h8a3 3 0 0 1 3 3M6 10V7h4v3" /></>,
    card: <><rect x="3" y="5" width="18" height="14" rx="2" /><path d="M3 10h18M7 15h4" /></>,
    shield: <><path d="M12 3 5 6v5c0 4.4 3 8 7 10 4-2 7-5.6 7-10V6Z" /><path d="m9 12 2 2 4-5" /></>,
    check: <path d="m5 12 4 4L19 6" />,
    info: <><circle cx="12" cy="12" r="9" /><path d="M12 11v5M12 8h.01" /></>,
    pin: <><path d="M12 21s7-5.1 7-11a7 7 0 1 0-14 0c0 5.9 7 11 7 11Z" /><circle cx="12" cy="10" r="2.5" /></>,
    phone: <path d="M7 3h3l2 5-2 1a12 12 0 0 0 5 5l1-2 5 2v3a2 2 0 0 1-2 2A17 17 0 0 1 5 5a2 2 0 0 1 2-2Z" />,
    mail: <><rect x="3" y="5" width="18" height="14" rx="2" /><path d="m4 7 8 6 8-6" /></>,
};

function Icon({ name, className = '' }) {
    return (
        <svg className={className} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
            {iconPaths[name]}
        </svg>
    );
}

function luminance(hex) {
    const value = hex.replace('#', '');
    const channels = [0, 2, 4].map((index) => parseInt(value.slice(index, index + 2), 16) / 255);
    const normalized = channels.map((value) => value <= 0.03928 ? value / 12.92 : ((value + 0.055) / 1.055) ** 2.4);
    return (0.2126 * normalized[0]) + (0.7152 * normalized[1]) + (0.0722 * normalized[2]);
}

function readableText(background) {
    return luminance(background) > 0.42 ? '#1A202C' : '#FFFFFF';
}

const details = [
    ['guestName', 'Guest Name', 'user'],
    ['confirmationReference', 'Confirmation Number', 'document'],
    ['checkIn', 'Check-In', 'calendarIn'],
    ['checkOut', 'Check-Out', 'calendarOut'],
    ['nights', 'Nights', 'moon'],
    ['mealPlan', 'Meal Plan', 'utensils'],
    ['occupancy', 'Occupancy', 'info'],
    ['roomType', 'Room Type', 'bed'],
];

export default function VoucherPreview({ settings, voucher, theme }) {
    const textOnPrimary = readableText(theme.primary);
    const textOnDark = readableText('#0B3B3E');
    const hotelPhotoUrl = voucher.hotelPhotoUrl || voucher.hotelPhoto;

    return (
        <div
            id="voucher-preview-node"
            className="voucher voucher-react-preview"
            data-voucher-theme
            style={{
                '--brand-primary': theme.primary,
                '--brand-accent': theme.accent,
                '--brand-surface-light': theme.lightSurface,
                '--brand-surface-dark': '#0B3B3E',
                '--text-on-dark': textOnDark,
                '--text-on-primary': textOnPrimary,
                '--text-on-light': '#1A202C',
                '--voucher-font': theme.font,
            }}
        >
            <div className="voucher-shell">
                <header className="voucher-header">
                    <div className="voucher-brand-row">
                        <div className="voucher-logo">
                            {settings.logoUrl
                                ? <img className="voucher-logo-image" src={settings.logoUrl} alt={`${settings.companyName} logo`} />
                                : <span className="voucher-logo-mark">AT</span>}
                        </div>
                        <span className="voucher-vertical-divider" aria-hidden="true" />
                        <div className="voucher-tagline">
                            <strong>{settings.tagline || 'Your Journey, OUR COMMITMENT'}</strong>
                            <span>ALMA TRAVEL</span>
                        </div>
                    </div>
                    <div className="voucher-title-block">
                        <span className="voucher-title-rule" />
                        <h1>CONFIRMATION VOUCHER</h1>
                        <span className="voucher-title-rule" />
                        <p>THANK YOU FOR CHOOSING ALMA TRAVEL</p>
                    </div>
                </header>

                <div className="voucher-content">
                    <div className="voucher-left-column">
                        <section className="voucher-details-section" aria-label="Booking details">
                            <div className="voucher-detail-grid">
                                {details.map(([key, label, iconName]) => (
                                    <div className="voucher-detail" key={key}>
                                        <span className="voucher-icon-badge"><Icon name={iconName} /></span>
                                        <div>
                                            <span className="voucher-detail-label">{label}</span>
                                            <strong className="voucher-detail-value">{voucher[key] || '—'}</strong>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </section>

                        <div className="guarantee-box">
                            <span className="guarantee-check"><Icon name="check" /></span>
                            <div>
                                <strong>BOOKING GUARANTEE</strong>
                                <p>YOUR BOOKING IS GUARANTEED BY ALMA TRAVEL</p>
                            </div>
                        </div>
                    </div>

                    <aside className="voucher-hotel-panel">
                        <span className="voucher-panel-curve" aria-hidden="true" />
                        <span className="voucher-section-kicker">HOTEL CONFIRMATION</span>
                        <h2>{voucher.hotelName || 'Hotel name'}</h2>
                        <div className="voucher-hotel-photo-wrap">
                            {hotelPhotoUrl
                                ? <img className="voucher-hotel-photo" src={hotelPhotoUrl} style={{ objectFit: 'cover', borderRadius: '12px' }} alt={voucher.hotelName || 'Hotel preview'} />
                                : <span className="voucher-hotel-photo-placeholder">Hotel photo preview</span>}
                        </div>
                        <div className="voucher-policy-card">
                            <span className="voucher-policy-heading">
                                <span className="voucher-icon-badge policy-icon-badge"><Icon name="info" /></span>
                                <span className="voucher-policy-label">CANCELLATION POLICY</span>
                            </span>
                            <p>{voucher.cancellationPolicy || 'Select a policy'}</p>
                        </div>
                    </aside>
                </div>

                <footer className="voucher-footer">
                    <div className="voucher-footer-contact">
                        <span className="voucher-footer-label"><span className="voucher-icon-badge footer-icon-badge"><Icon name="pin" /></span> COMPANY ADDRESS</span>
                        <strong>{settings.address || 'Alma Travel, Jeddah, Saudi Arabia'}</strong>
                    </div>
                    <div className="voucher-footer-consultant">
                        <span className="voucher-footer-label"><span className="voucher-icon-badge footer-icon-badge"><Icon name="user" /></span> BOOKING CONSULTANT</span>
                        <strong>{voucher.consultantName || 'Alma Travel Team'}</strong>
                        <span>{voucher.consultantContact || 'Phone · Email'}</span>
                    </div>
                    <div className="voucher-footer-partners">
                        <span className="voucher-footer-label">WITH COMPLIMENTS</span>
                        <div className="partner-logo"><span>LUFTHANSA</span><span>CITY CENTER</span></div>
                    </div>
                </footer>
            </div>
        </div>
    );
}
