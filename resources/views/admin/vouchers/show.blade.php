@extends('layouts.admin')
@section('page-title', 'Voucher log')
@section('content')
<div class="admin-card">
    <div class="admin-card-heading d-flex align-items-center justify-content-between">
        <div>
            <p class="eyebrow mb-1">{{ $voucher->typeLabel() }} · Voucher record</p>
            <h2>{{ $voucher->voucher_number }}</h2>
        </div>
        <div class="d-flex gap-2">
            @if ($voucher->publicPdfRoute())
                <a class="btn btn-outline-dark btn-sm" href="{{ route($voucher->publicPdfRoute(), $voucher) }}">Download PDF</a>
            @endif
            @if ($voucher->publicShowRoute())
                <a class="btn btn-alma btn-sm" href="{{ route($voucher->publicShowRoute(), $voucher) }}" target="_blank" rel="noopener">Open preview</a>
            @endif
        </div>
    </div>

    {{-- Shared booking fields --}}
    <div class="record-grid">
        <div><span>Guest</span><strong>{{ $voucher->guest_name ?: '—' }}</strong></div>
        <div><span>Booking number</span><strong>{{ $voucher->booking_number ?: '—' }}</strong></div>
        <div><span>Confirmation / PNR</span><strong>{{ $voucher->confirmation_reference ?: '—' }}</strong></div>
        <div><span>Consultant</span><strong>{{ $voucher->employee?->name ?? '—' }}</strong></div>
    </div>

    @if ($voucher->voucher_type === 'hotel')
        <div class="record-grid">
            <div><span>Hotel</span><strong>{{ $voucher->hotel_name ?: '—' }}</strong></div>
            <div><span>Hotel phone</span><strong>{{ $voucher->hotel_phone ?: '—' }}</strong></div>
            <div><span>Check-in</span><strong>{{ $voucher->check_in_date?->format('d M Y') ?? '—' }}{{ $voucher->check_in_time ? ' · '.$voucher->check_in_time : '' }}</strong></div>
            <div><span>Check-out</span><strong>{{ $voucher->check_out_date?->format('d M Y') ?? '—' }}{{ $voucher->check_out_time ? ' · '.$voucher->check_out_time : '' }}</strong></div>
            <div><span>Nights</span><strong>{{ $voucher->nights_count ?? '—' }}</strong></div>
            <div><span>Room type</span><strong>{{ $voucher->room_type ?: '—' }}</strong></div>
            <div><span>Meal plan</span><strong>{{ $voucher->meal_plan ?: '—' }}</strong></div>
            <div><span>Occupancy</span><strong>{{ $voucher->occupancy ?: '—' }}</strong></div>
        </div>
        @if ($voucher->cancellation_policy)
            <div class="record-policy"><strong>Cancellation policy</strong><p>{{ $voucher->cancellation_policy }}</p></div>
        @endif
    @elseif ($voucher->voucher_type === 'car-rental')
        <div class="record-grid">
            <div><span>Rental duration</span><strong>{{ $voucher->rental_duration ? $voucher->rental_duration.' '.($voucher->rental_duration == 1 ? 'Day' : 'Days') : '—' }}</strong></div>
            <div><span>Branch timing</span><strong>{{ $voucher->branch_timing ?: '—' }}</strong></div>
            <div><span>Supplier reference</span><strong>{{ $voucher->supplier_reference ?: '—' }}</strong></div>
            <div><span>Flight</span><strong>{{ trim(trim((string) $voucher->flight_number).' '.($voucher->flight_arrival_date?->format('d M Y') ?? '').' '.trim((string) ($voucher->flight_arrival_time ?? ''))) ?: '—' }}{{ $voucher->flight_airport ? ' · '.$voucher->flight_airport : '' }}</strong></div>
            <div><span>Vehicle</span><strong>{{ trim(($voucher->vehicle_type ?? '').($voucher->vehicle_model ? ' · '.$voucher->vehicle_model : '')) ?: '—' }}</strong></div>
            <div><span>Insurance</span><strong>{{ $voucher->insurance_type ?: '—' }}</strong></div>
            <div><span>Pickup</span><strong>{{ $voucher->check_in_date?->format('d M Y') ?? '—' }}{{ $voucher->check_in_time ? ' · '.$voucher->check_in_time : '' }}<br><span class="muted">{{ $voucher->pickup_location }}{{ $voucher->pickup_terminal ? ' · '.$voucher->pickup_terminal : '' }}</span></strong></div>
            <div><span>Drop-off</span><strong>{{ $voucher->check_out_date?->format('d M Y') ?? '—' }}{{ $voucher->check_out_time ? ' · '.$voucher->check_out_time : '' }}<br><span class="muted">{{ $voucher->dropoff_location }}{{ $voucher->dropoff_terminal ? ' · '.$voucher->dropoff_terminal : '' }}</span></strong></div>
        </div>
        @if ($voucher->important_notes)
            <div class="record-policy"><strong>Important notes</strong><p>{{ $voucher->important_notes }}</p></div>
        @endif
    @elseif ($voucher->voucher_type === 'transfers')
        <div class="record-grid">
            <div><span>Trip type</span><strong>{{ ($voucher->trip_type ?? 'one_way') === 'round_trip' ? 'Round Trip' : 'One Way' }}</strong></div>
            <div><span>Booking date</span><strong>{{ $voucher->booking_date?->format('d M Y') ?? '—' }}</strong></div>
            <div><span>Guest mobile</span><strong>{{ $voucher->guest_mobile ?: '—' }}</strong></div>
            <div><span>Flight</span><strong>{{ trim(trim((string) $voucher->flight_number).' '.($voucher->flight_date?->format('d M Y') ?? '').' '.trim((string) ($voucher->flight_time ?? ''))) ?: '—' }}</strong></div>
            <div><span>Driver</span><strong>{{ $voucher->driver_name ?: '—' }}{{ $voucher->driver_number ? ' · '.$voucher->driver_number : '' }}</strong></div>
            <div><span>Pickup</span><strong>{{ $voucher->check_in_date?->format('d M Y') ?? '—' }}{{ $voucher->check_in_time ? ' · '.$voucher->check_in_time : '' }}<br><span class="muted">{{ $voucher->pickup_location }}</span></strong></div>
            <div><span>Drop-off</span><strong>{{ $voucher->dropoff_location ?: '—' }}</strong></div>
            <div><span>Vehicle</span><strong>{{ $voucher->vehicle_type ?: '—' }}</strong></div>
            <div><span>Passengers / Luggage</span><strong>{{ $voucher->passengers ?? '—' }} / {{ $voucher->luggage_count ?? '—' }}</strong></div>
            @if (($voucher->trip_type ?? 'one_way') === 'round_trip')
            <div><span>Return pickup</span><strong>{{ $voucher->return_pickup_date?->format('d M Y') ?? '—' }}{{ $voucher->return_pickup_time ? ' · '.$voucher->return_pickup_time : '' }}<br><span class="muted">{{ $voucher->return_pickup_location }}</span></strong></div>
            <div><span>Return drop-off</span><strong>{{ $voucher->return_dropoff_date?->format('d M Y') ?? '—' }}{{ $voucher->return_dropoff_time ? ' · '.$voucher->return_dropoff_time : '' }}<br><span class="muted">{{ $voucher->return_dropoff_location }}</span></strong></div>
            <div><span>Return flight</span><strong>{{ trim(trim((string) $voucher->return_flight_number).' '.($voucher->return_flight_date?->format('d M Y') ?? '').' '.trim((string) ($voucher->return_flight_time ?? ''))) ?: '—' }}</strong></div>
            @endif
        </div>
        @if ($voucher->special_requests)
            <div class="record-policy"><strong>Special requests</strong><p>{{ $voucher->special_requests }}</p></div>
        @endif
    @elseif ($voucher->voucher_type === 'vip-meet-assist')
        @php
            $assists = collect([
                'Meet & Assist' => $voucher->service_meet_assist,
                'Private car' => $voucher->service_private_car,
                'Fast track' => $voucher->fast_track,
                'VIP lounge' => $voucher->vip_lounge_access,
                'Immigration' => $voucher->service_immigration,
            ]);
            $assistsOn = $assists->filter()->keys()->implode(', ');
            $assistsAll = $assists->map(fn ($on, $label) => $label.': '.($on ? 'Yes' : 'No'))->implode(' · ');
        @endphp
        <div class="record-grid">
            <div><span>Agent</span><strong>{{ $voucher->company_name ?: '—' }}</strong></div>
            <div><span>Agent phone</span><strong>{{ $voucher->company_phone ?: '—' }}</strong></div>
            <div><span>Service type</span><strong>{{ $voucher->service_type ?: '—' }}</strong></div>
            <div><span>Flight</span><strong>{{ trim(trim((string) $voucher->airline).' '.trim((string) $voucher->flight_number)) ?: '—' }}</strong></div>
            <div><span>Arrival time</span><strong>{{ $voucher->flight_arrival_time ?: '—' }}</strong></div>
            <div><span>Meeting point</span><strong>{{ $voucher->meeting_point ?: '—' }}{{ $voucher->meeting_time ? ' · '.$voucher->meeting_time : '' }}</strong></div>
            <div><span>Terminal</span><strong>{{ $voucher->terminal ?: '—' }}</strong></div>
            <div><span>Number of Passengers</span><strong>{{ $voucher->passengers ?? '—' }}</strong></div>
        </div>
        <div class="record-policy"><strong>Services</strong><p>{{ $assistsAll }}</p></div>
        @if ($voucher->special_requests)
            <div class="record-policy"><strong>Special requests</strong><p>{{ $voucher->special_requests }}</p></div>
        @endif
    @endif

    @if ($voucher->voucher_type === 'hotel' && $voucher->special_requests)
        <div class="record-policy"><strong>Special requests</strong><p>{{ $voucher->special_requests }}</p></div>
    @endif

    <div class="record-files"><strong>Stored exports</strong><div>@if ($voucher->pdf_path)<a href="{{ $voucher->pdfUrl() }}" target="_blank" rel="noopener">Open PDF</a>@else<span class="muted">No PDF generated</span>@endif @if ($voucher->png_path)<a href="{{ $voucher->pngUrl() }}" target="_blank" rel="noopener">Open PNG</a>@else<span class="muted">No PNG generated</span>@endif</div></div>
    <div class="form-actions admin-form-actions"><a class="btn btn-outline-dark" href="{{ route('admin.vouchers.index') }}">Back to logs</a><form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}">@csrf @method('DELETE')<button class="btn btn-outline-danger" type="submit" onclick="return confirm('Delete this voucher log permanently?')">Delete log</button></form></div>
</div>
@endsection
