export const voucherThemes = {
    alma_classic: {
        primary: '#054446',
        accent: '#C5A059',
        lightSurface: '#FFFFFF',
        darkSurface: '#0B3B3E',
        font: '"Playfair Display", Georgia, serif',
    },
    alma_modern: {
        primary: '#0F2537',
        accent: '#2563EB',
        lightSurface: '#FFFFFF',
        darkSurface: '#091E2F',
        font: '"Inter", Helvetica, Arial, sans-serif',
    },
    alma_premium: {
        primary: '#B5322B',
        accent: '#D4AF37',
        lightSurface: '#FFFFFF',
        darkSurface: '#1C1917',
        font: '"Cinzel", Georgia, serif',
    },
};

const icon = (paths) => `<svg class="voucher-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${paths}</svg>`;

export const voucherIconMap = {
    guestName: icon('<path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z"/><path d="M5 20c.7-3.2 3.5-5 7-5s6.3 1.8 7 5"/>'),
    confirmationNumber: icon('<path d="M6 3h9l5 5v13H6Z"/><path d="M14 3v6h6M9 13h7M9 17h5"/>'),
    checkIn: icon('<path d="M4 5h16v16H4ZM4 9h16M8 3v4m8-4v4"/><path d="M12 12v5m0 0 2-2m-2 2-2-2"/>'),
    checkOut: icon('<path d="M4 5h16v16H4ZM4 9h16M8 3v4m8-4v4"/><path d="M12 17v-5m0 0-2 2m2-2 2 2"/>'),
    nights: icon('<path d="M19 15A8 8 0 1 1 9 5a6.5 6.5 0 0 0 10 10Z"/>'),
    mealPlan: icon('<path d="M7 3v7M4 3v5a3 3 0 0 0 6 0V3M7 10v11M17 3c-2 0-3 2.5-3 5v3h3v10M17 3v18"/>'),
    roomType: icon('<path d="M3 18v-8m0 5h18v3M3 15h7v-3h8a3 3 0 0 1 3 3M6 10V7h4v3"/>'),
    guarantee: icon('<path d="M12 3 5 6v5c0 4.4 3 8 7 10 4-2 7-5.6 7-10V6Z"/><path d="m9 12 2 2 4-5"/>'),
    cancellationPolicy: icon('<circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/>'),
    address: icon('<path d="M12 21s7-5.1 7-11a7 7 0 1 0-14 0c0 5.9 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/>'),
};

const normalizeHex = (value, fallback) => {
    const candidate = String(value || '').trim();
    return /^#[0-9a-f]{6}$/i.test(candidate) ? candidate.toUpperCase() : fallback;
};

const luminance = (hex) => {
    const value = hex.replace('#', '');
    const channels = [0, 2, 4].map((index) => parseInt(value.slice(index, index + 2), 16) / 255);
    const normalized = channels.map((value) => value <= 0.03928 ? value / 12.92 : ((value + 0.055) / 1.055) ** 2.4);

    return (0.2126 * normalized[0]) + (0.7152 * normalized[1]) + (0.0722 * normalized[2]);
};

const bestContrastColor = (background) => luminance(background) > 0.42 ? '#1A202C' : '#FFFFFF';

export function setVoucherTheme(themeOrPrimary, accentColor, surfaceDarkColor, root = document.querySelector('[data-voucher-theme]')) {
    if (!root) return;

    const selectedTheme = voucherThemes[themeOrPrimary];
    const primary = normalizeHex(selectedTheme?.primary || themeOrPrimary, '#054446');
    const accent = normalizeHex(selectedTheme?.accent || accentColor, '#C5A059');
    const surfaceLight = normalizeHex(selectedTheme?.lightSurface || root.dataset.themeSurfaceLight, '#FFFFFF');
    const surfaceDark = normalizeHex(selectedTheme?.darkSurface || surfaceDarkColor, '#0B3B3E');
    const font = selectedTheme?.font || root.dataset.themeFont || '"Playfair Display", Georgia, serif';

    root.style.setProperty('--brand-primary', primary);
    root.style.setProperty('--brand-accent', accent);
    root.style.setProperty('--brand-surface-light', surfaceLight);
    root.style.setProperty('--brand-surface-dark', surfaceDark);
    root.style.setProperty('--text-on-light', '#1A202C');
    root.style.setProperty('--text-on-dark', bestContrastColor(surfaceDark));
    root.style.setProperty('--text-on-primary', bestContrastColor(primary));
    root.style.setProperty('--voucher-font', font);
    root.dataset.themePrimary = primary;
    root.dataset.themeAccent = accent;
    root.dataset.themeSurfaceLight = surfaceLight;
    root.dataset.themeSurfaceDark = surfaceDark;
    root.dataset.themeFont = font;
}

export function applyVoucherTheme(themeName, root = document.querySelector('[data-voucher-theme]')) {
    setVoucherTheme(themeName, undefined, undefined, root);
}

export function applyVoucherIcons(root = document.querySelector('[data-voucher-theme]')) {
    if (!root) return;

    root.querySelectorAll('[data-voucher-icon]').forEach((badge) => {
        const iconMarkup = voucherIconMap[badge.dataset.voucherIcon];
        if (iconMarkup) badge.innerHTML = iconMarkup;
    });
}

export function initializeVoucherTheme(root = document.querySelector('[data-voucher-theme]')) {
    if (!root) return;

    setVoucherTheme(
        root.dataset.themePrimary,
        root.dataset.themeAccent,
        root.dataset.themeSurfaceDark,
        root,
    );
    applyVoucherIcons(root);
}

window.voucherThemes = voucherThemes;
window.voucherIconMap = voucherIconMap;
window.setVoucherTheme = setVoucherTheme;
window.applyVoucherTheme = applyVoucherTheme;
window.applyVoucherIcons = applyVoucherIcons;

document.addEventListener('DOMContentLoaded', initializeVoucherTheme);
