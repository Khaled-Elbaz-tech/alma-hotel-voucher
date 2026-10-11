const DEFAULT_THEME = {
    primary: '#B5322B',
    accent: '#B89759',
    surfaceDark: '#1C1917',
    surfaceLight: '#FFFFFF',
    textOnLight: '#1A202C',
};

const normalizeHex = (value, fallback) => {
    const candidate = String(value || '').trim();
    return /^#[0-9a-f]{6}$/i.test(candidate) ? candidate.toUpperCase() : fallback;
};

const hexToRgb = (hex) => {
    const value = hex.replace('#', '');
    return {
        r: parseInt(value.slice(0, 2), 16),
        g: parseInt(value.slice(2, 4), 16),
        b: parseInt(value.slice(4, 6), 16),
    };
};

const luminance = (hex) => {
    const { r, g, b } = hexToRgb(hex);
    const channel = (value) => {
        const normalized = value / 255;
        return normalized <= 0.03928 ? normalized / 12.92 : ((normalized + 0.055) / 1.055) ** 2.4;
    };
    return (0.2126 * channel(r)) + (0.7152 * channel(g)) + (0.0722 * channel(b));
};

const bestContrastColor = (background) => {
    const dark = luminance(DEFAULT_THEME.textOnLight);
    const light = luminance('#FFFFFF');
    const backgroundLuminance = luminance(background);
    const darkContrast = (backgroundLuminance + 0.05) / (dark + 0.05);
    const lightContrast = (light + 0.05) / (backgroundLuminance + 0.05);
    return darkContrast >= lightContrast ? DEFAULT_THEME.textOnLight : '#FFFFFF';
};

export function setVoucherTheme(primaryColor, accentColor, surfaceDarkColor, root = document.querySelector('[data-voucher-theme]')) {
    if (!root) return;

    const primary = normalizeHex(primaryColor, DEFAULT_THEME.primary);
    const accent = normalizeHex(accentColor, DEFAULT_THEME.accent);
    const surfaceDark = normalizeHex(surfaceDarkColor, DEFAULT_THEME.surfaceDark);

    const surfaceLight = normalizeHex(root.dataset.themeSurfaceLight, DEFAULT_THEME.surfaceLight);

    root.style.setProperty('--brand-primary', primary);
    root.style.setProperty('--brand-accent', accent);
    root.style.setProperty('--brand-surface-light', surfaceLight);
    root.style.setProperty('--brand-surface-dark', surfaceDark);
    root.style.setProperty('--text-on-light', DEFAULT_THEME.textOnLight);
    root.style.setProperty('--text-on-dark', bestContrastColor(surfaceDark));
    root.style.setProperty('--text-on-primary', bestContrastColor(primary));
    root.dataset.themePrimary = primary;
    root.dataset.themeAccent = accent;
    root.dataset.themeSurfaceDark = surfaceDark;
}

window.setVoucherTheme = setVoucherTheme;

document.addEventListener('DOMContentLoaded', () => {
    const voucher = document.querySelector('[data-voucher-theme]');
    if (!voucher) return;

    setVoucherTheme(
        voucher.dataset.themePrimary,
        voucher.dataset.themeAccent,
        voucher.dataset.themeSurfaceDark,
        voucher,
    );
});
