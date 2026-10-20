@php
    $imgSrc = null;
    if ($voucher->png_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($voucher->png_path)) {
        $imgSrc = $message->embed(storage_path('app/public/'.$voucher->png_path));
    }
@endphp
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alma Travel — Voucher {{ $voucher->voucher_number }}</title>
</head>
<body style="margin: 0; padding: 24px; background-color: #eceae6; font-family: Arial, Helvetica, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0">
<tr><td align="center">
<table width="1000" cellpadding="0" cellspacing="0" style="max-width: 1000px; width: 100%;">
<tr><td style="background-color: #8B251E; padding: 16px 24px;">
    <span style="color: #ffffff; font-size: 19px; font-weight: bold; letter-spacing: 0.5px;">Alma Travel</span>
    <span style="color: #f3d9d5; font-size: 10px; letter-spacing: 3px;">&nbsp;&nbsp;MORE THAN A JOURNEY</span>
</td></tr>
<tr><td style="background-color: #ffffff; padding: 18px 24px 6px; color: #333333; font-size: 14px;">
    Dear {{ $voucher->user->name ?? 'Guest' }},<br>
    Your voucher <strong>{{ $voucher->voucher_number }}</strong> is below.@if($voucher->pdf_path) A PDF copy is attached for your records.@endif
</td></tr>
<tr><td style="background-color: #ffffff; padding: 10px 24px 20px;">
    @if($imgSrc)
    <img src="{{ $imgSrc }}" alt="Voucher {{ $voucher->voucher_number }}" width="952" style="display: block; width: 100%; max-width: 952px; height: auto; border: 1px solid #dddddd;">
    @else
    <span style="color: #666666; font-size: 13px;">Your voucher is attached as a PDF.</span>
    @endif
</td></tr>
<tr><td style="background-color: #2b2b2b; padding: 12px 24px; color: #bbbbbb; font-size: 11px;">
    Alma Travel @if(!empty($settings?->phone)) &nbsp;|&nbsp; {{ $settings->phone }} @endif @if(!empty($settings?->email)) &nbsp;|&nbsp; {{ $settings->email }} @endif
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>
