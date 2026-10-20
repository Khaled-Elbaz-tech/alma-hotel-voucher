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
        const btn = document.getElementById('printVoucherBtn');
        if (btn) btn.addEventListener('click', () => window.print());
        @if(session('success') && str_contains(session('success'), 'generated successfully'))
        setTimeout(function() {
            const el = document.getElementById('printVoucher');
            if (!el || typeof html2pdf === 'undefined') return;
            function postSave(pdfData, pngData) {
                fetch('{{ route('transfers-voucher.save-pdf', $voucher) }}', {
                    method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({pdf: pdfData, png: pngData})
                }).then(r => r.json()).then(d => { if (d.saved) { window.location.href = '{{ route('transfers-voucher.email-pdf', $voucher) }}'; } }).catch(e => console.error(e));
            }
            html2pdf().set({margin: 0, filename: '{{ $voucher->voucher_number }}.pdf', image: {type: 'jpeg', quality: 0.98}, html2canvas: {scale: 2, useCORS: true}, jsPDF: {unit: 'mm', format: 'a4', orientation: 'landscape'}}).from(el).output('datauristring').then(function(pdfData) {
                html2pdf().set({html2canvas: {scale: 2, useCORS: true}}).from(el).toCanvas().then(function(canvas) {
                    var pngData = null;
                    try { pngData = canvas.toDataURL('image/jpeg', 0.92); } catch (e) {}
                    postSave(pdfData, pngData);
                }).catch(function() { postSave(pdfData, null); });
            });
        }, 2000);
        @endif
    });
</script>
@endsection
