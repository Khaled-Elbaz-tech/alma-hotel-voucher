import React, { useMemo } from 'react';
import { createRoot } from 'react-dom/client';
import VoucherFormPreview from './components/VoucherFormPreview';

const mountPoint = document.getElementById('voucher-react-root');
if (mountPoint) {
    const settings = JSON.parse(mountPoint.dataset.settings || '{}');
    createRoot(mountPoint).render(<VoucherFormPreview settings={settings} />);
}
