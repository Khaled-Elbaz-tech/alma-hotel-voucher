import React, { useCallback, useEffect, useMemo, useState } from 'react';
import html2pdf from 'html2pdf.js';
import ThemeSelector from './ThemeSelector';
import VoucherPreview from './VoucherPreview';
import { applyVoucherTheme, voucherThemes } from '../voucherThemes';

const initialVoucher = {
    guestName: 'Guest name',
    confirmationReference: 'Reference',
    checkIn: 'Select date',
    checkOut: 'Select date',
    nights: '0 nights',
    mealPlan: 'Select option',
    roomType: 'Select option',
    occupancy: '1 Room | 2 Adults',
    cancellationPolicy: 'Select a policy',
    hotelName: 'Hotel name',
    hotelPhoto: '',
    consultantName: 'Alma Travel Team',
    consultantContact: 'Phone · Email',
};

function VoucherFormPreview({ settings }) {
    const [activeTheme, setActiveTheme] = useState(settings.voucherTheme || 'alma_classic');
    const [voucher, setVoucher] = useState(initialVoucher);
    const [isExporting, setIsExporting] = useState(false);

    useEffect(() => {
        const handlePreviewUpdate = (event) => setVoucher(event.detail || initialVoucher);
        window.addEventListener('voucherPreviewUpdate', handlePreviewUpdate);

        return () => window.removeEventListener('voucherPreviewUpdate', handlePreviewUpdate);
    }, []);

    useEffect(() => {
        applyVoucherTheme(activeTheme, document.getElementById('voucher-preview-node'));
    }, [activeTheme]);

    const handleThemeChange = useCallback((themeName) => {
        setActiveTheme(themeName);
        window.dispatchEvent(new CustomEvent('voucherThemeChange', { detail: { themeName } }));
    }, []);

    const handleExportPDF = useCallback(async () => {
        const node = document.getElementById('voucher-preview-node');
        if (!node) return;

        const previousStyles = {
            display: node.style.display,
            visibility: node.style.visibility,
            opacity: node.style.opacity,
            position: node.style.position,
            zIndex: node.style.zIndex,
        };
        const images = Array.from(node.querySelectorAll('img'));
        setIsExporting(true);
        node.classList.add('voucher-exporting');

        try {
            await document.fonts?.ready;
            await Promise.all(images.map((image) => image.complete
                ? Promise.resolve()
                : new Promise((resolve, reject) => {
                    image.addEventListener('load', resolve, { once: true });
                    image.addEventListener('error', reject, { once: true });
                })));

            node.style.display = 'block';
            node.style.visibility = 'visible';
            node.style.opacity = '1';
            node.style.position = 'relative';
            node.style.zIndex = '1';

            const fileName = `Alma-Travel-Voucher-${(voucher.confirmationReference || 'preview').replace(/[^a-z0-9-_]+/gi, '-') || 'preview'}.pdf`;
            const options = {
                margin: 0,
                filename: fileName,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    scrollX: 0,
                    scrollY: 0,
                    windowWidth: node.scrollWidth,
                    windowHeight: node.scrollHeight,
                },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' },
                pagebreak: {
                    mode: ['css', 'legacy'],
                    avoid: ['.voucher-header', '.voucher-content', '.voucher-details-section', '.voucher-hotel-panel', '.guarantee-box', '.voucher-policy-card', '.voucher-footer'],
                },
            };

            await html2pdf().set(options).from(node).save();
        } finally {
            node.classList.remove('voucher-exporting');
            node.style.display = previousStyles.display;
            node.style.visibility = previousStyles.visibility;
            node.style.opacity = previousStyles.opacity;
            node.style.position = previousStyles.position;
            node.style.zIndex = previousStyles.zIndex;
            setIsExporting(false);
        }
    }, [voucher.confirmationReference]);

    useEffect(() => {
        window.handleExportPDF = handleExportPDF;
        window.voucherPreviewApi = { handleExportPDF, setVoucher };

        return () => {
            delete window.handleExportPDF;
            delete window.voucherPreviewApi;
        };
    }, [handleExportPDF, setVoucher]);

    return (
        <div className="voucher-form-preview">
            <div className="voucher-preview-actions">
                <button id="getVoucher" className="btn btn-alma get-voucher-button" type="button" onClick={handleExportPDF} disabled={isExporting}>
                    {isExporting ? 'Preparing PDF…' : 'Get Voucher'}
                </button>
                <span>Exports the live preview as a single A4 landscape PDF.</span>
            </div>
            <ThemeSelector activeTheme={activeTheme} onThemeChange={handleThemeChange} />
            <VoucherPreview settings={settings} voucher={voucher} theme={voucherThemes[activeTheme] || voucherThemes.alma_classic} />
        </div>
    );
}

export default VoucherFormPreview;
