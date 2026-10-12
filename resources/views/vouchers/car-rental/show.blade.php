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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('printVoucherBtn').addEventListener('click', () => window.print());
    });
</script>
@endsection
