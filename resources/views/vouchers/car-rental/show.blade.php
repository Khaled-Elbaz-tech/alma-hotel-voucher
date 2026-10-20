@extends('layouts.app')
@section('title', $voucher->voucher_number . ' | Alma Travel')
@section('content')
<section class="result-page">
    <div class="container-xl">
        <div class="result-nav mb-4">
            <a class="back-link light" href="{{ route('car-rental-voucher.create') }}">← Create another voucher</a>
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
            </div>
        </div>
        <div class="result-layout">
            <div class="result-preview">
                 @include('vouchers.car-rental.partials.voucher-layout', ['settings' => $settings, 'voucher' => $voucher])
            </div>
        </div>
    </div>
</section>
@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('printVoucherBtn');
        if (btn) btn.addEventListener('click', () => window.print());
        @if(session('success') && str_contains(session('success'), 'generated successfully'))
        setTimeout(function() {
            const el = document.getElementById('printVoucher');
            if (!el || typeof html2pdf === 'undefined') return;
            function mailStatus(ok, msg) {
                var b = document.createElement('div');
                b.textContent = msg;
                b.style.cssText = 'position:sticky;top:0;z-index:9999;padding:12px 16px;text-align:center;font-weight:700;font-size:14px;color:#fff;background:' + (ok ? '#1e7e34' : '#b02a20');
                document.body.prepend(b);
            }
            function postSave(pdfData) {
                fetch('{{ route('car-rental-voucher.save-pdf', $voucher) }}', {
                    method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({pdf: pdfData})
                }).then(function(r) {
                    if (!r.ok) {
                        return r.json().catch(function() { return {}; }).then(function(d) {
                            mailStatus(false, '⚠ Could not save the voucher — ' + (d.message || ('server ' + r.status)) + ' — the email was not sent.');
                            return null;
                        });
                    }
                    return r.json();
                }).then(function(d) {
                    if (!d) return;
                    if (d.saved && d.emailed) { mailStatus(true, '✓ Voucher saved and emailed successfully.'); }
                    else if (d.saved) { mailStatus(false, '⚠ Voucher saved, but the email failed' + (d.error ? ': ' + d.error : '.')); }
                }).catch(function(e) { mailStatus(false, '⚠ Could not reach the server to save the voucher — the email was not sent.'); });
            }
            function postImageThenPdf(pdfData, imgData) {
                if (!imgData) { postSave(pdfData); return; }
                fetch('{{ route('car-rental-voucher.save-png', $voucher) }}', {
                    method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({image: imgData})
                }).then(function() { postSave(pdfData); }).catch(function() { postSave(pdfData); });
            }
            html2pdf().set({margin: 0, filename: '{{ $voucher->voucher_number }}.pdf', image: {type: 'jpeg', quality: 0.98}, html2canvas: {scale: 2, useCORS: true, scrollY: -window.scrollY}, jsPDF: {unit: 'mm', format: 'a4', orientation: 'landscape'}}).from(el).output('datauristring').then(function(pdfData) {
                html2pdf().set({html2canvas: {scale: 2, useCORS: true, scrollY: -window.scrollY}}).from(el).toCanvas().then(function(canvas) {
                    var imgData = null;
                    try { imgData = canvas.toDataURL('image/jpeg', 0.92); } catch (e) {}
                    postImageThenPdf(pdfData, imgData);
                }).catch(function() { postSave(pdfData); });
            });
        }, 2000);
        @endif
    });
</script>
@endsection
