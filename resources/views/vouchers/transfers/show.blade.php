@extends('layouts.app')
@section('title', $voucher->voucher_number . ' | Alma Travel')
@section('content')
<section class="result-page">
    <div class="container-xl">
        <div class="result-nav mb-4">
            <a class="back-link light" href="{{ route('transfers-voucher.create') }}">← Create another voucher</a>
            @if (session('success'))<span class="success-message">{{ session('success') }}</span>@endif
        </div>
        <div class="result-heading row align-items-end">
            <div class="col-lg-8">
                <p class="eyebrow">Voucher generated</p>
                <h1>{{ $voucher->voucher_number }}</h1>
                <p class="lead mb-0">The confirmation is ready to download, share, or print.</p>
            </div>
            <div class="col-lg-4 result-actions">
                <button class="btn btn-alma btn-lg" id="printVoucherBtn" type="button">Print voucher</button>
                <button class="btn btn-outline-alma btn-lg" id="emailPdfBtn" type="button">📧 Email PDF</button>
                <div id="emailStatus" class="mt-2 small"></div>
            </div>
        </div>
        <div class="result-layout">
            <div class="result-preview">
                 @include('vouchers.transfers.partials.voucher-layout', ['settings' => $settings, 'voucher' => $voucher])
            </div>
        </div>
    </div>
</section>
@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('printVoucherBtn').addEventListener('click', () => window.print());
        
        // Manual email trigger
        const emailBtn = document.getElementById('emailPdfBtn');
        const emailStatus = document.getElementById('emailStatus');
        function sendVoucherEmail() {
            if (emailStatus) emailStatus.innerHTML = '<span class="text-info">Generating PDF...</span>';
            const element = document.getElementById('printVoucher');
            if (!element) { if (emailStatus) emailStatus.innerHTML = '<span class="text-danger">Voucher element not found.</span>'; return; }
            if (typeof html2pdf === 'undefined') { if (emailStatus) emailStatus.innerHTML = '<span class="text-danger">PDF library not loaded. Check internet connection.</span>'; return; }
            const opt = {
                margin: 0,
                filename: '{{ $voucher->voucher_number }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };
            html2pdf().set(opt).from(element).output('datauristring').then(function(pdfData) {
                if (emailStatus) emailStatus.innerHTML = '<span class="text-info">Uploading...</span>';
                fetch('{{ route('transfers-voucher.save-pdf', $voucher) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ pdf: pdfData })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.emailed) {
                        if (emailStatus) emailStatus.innerHTML = '<span class="text-success">✅ Emailed to {{ auth()->user()->email }}</span>';
                    } else {
                        if (emailStatus) emailStatus.innerHTML = '<span class="text-warning">⚠️ Emailed to but email failed: ' + (data.error || 'unknown') + '</span>';
                    }
                })
                .catch(e => { if (emailStatus) emailStatus.innerHTML = '<span class="text-danger">❌ Upload failed: ' + e.message + '</span>'; });
            }).catch(e => { if (emailStatus) emailStatus.innerHTML = '<span class="text-danger">❌ PDF generation failed: ' + e.message + '</span>'; });
        }
        if (emailBtn) emailBtn.addEventListener('click', sendVoucherEmail);

        // Auto-generate PDF and email it (only on fresh voucher creation)
        @if(session('success') && str_contains(session('success'), 'generated successfully'))
        setTimeout(function() {
            const element = document.getElementById('printVoucher');
            if (!element) return;

            const opt = {
                margin: 0,
                filename: '{{ $voucher->voucher_number }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };

            html2pdf().set(opt).from(element).output('datauristring').then(function(pdfData) {
                fetch('{{ route('transfers-voucher.save-pdf', $voucher) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ pdf: pdfData })
                })
                .then(r => r.json())
                .then(data => {
                    const msg = document.querySelector('.success-message');
                    if (msg && data.emailed) {
                        msg.textContent += ' Emailed to {{ auth()->user()->email }}.';
                    }
                })
                .catch(e => console.error('PDF upload failed:', e));
            });
        }, 1500);
        @endif
    });
</script>
@endsection
