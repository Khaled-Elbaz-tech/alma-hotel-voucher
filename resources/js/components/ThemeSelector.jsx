import { voucherThemes } from '../voucherThemes';

const themeLabels = {
    alma_classic: 'Alma Classic',
    alma_modern: 'Alma Modern',
    alma_premium: 'Alma Premium',
};

export default function ThemeSelector({ activeTheme, onThemeChange }) {
    return (
        <div className="theme-selector" aria-label="Voucher theme selector">
            {Object.entries(voucherThemes).map(([themeName, theme]) => (
                <button
                    aria-pressed={activeTheme === themeName}
                    className={`theme-card ${activeTheme === themeName ? 'is-active' : ''}`}
                    key={themeName}
                    onClick={() => onThemeChange(themeName)}
                    style={{
                        '--card-primary': theme.primary,
                        '--card-accent': theme.accent,
                        '--card-light': theme.lightSurface,
                        '--card-dark': theme.darkSurface,
                        '--card-font': theme.font,
                    }}
                    type="button"
                >
                    <span className="theme-card-visual">
                        <span className="theme-card-header">
                            <span className="theme-card-logo">AT</span>
                            <span className="theme-card-title">CONFIRMATION<br />VOUCHER</span>
                        </span>
                        <span className="theme-card-body">
                            <span className="theme-card-detail" />
                            <span className="theme-card-detail short" />
                            <span className="theme-card-detail" />
                        </span>
                    </span>
                    <span className="theme-card-copy">
                        <strong>{themeLabels[themeName]}</strong>
                        <span>{theme.font.includes('Inter') ? 'Clean corporate sans-serif' : 'Editorial luxury serif'}</span>
                    </span>
                    <span className="theme-active-indicator" aria-hidden="true">Active</span>
                </button>
            ))}
        </div>
    );
}
