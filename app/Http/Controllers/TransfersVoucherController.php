<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Voucher;

class TransfersVoucherController extends BaseVoucherController
{
    protected string $voucherType = 'transfers';
    protected string $voucherPrefix = 'ALMA-TR';
    protected string $createView = 'vouchers.transfers.create';
    protected string $showView = 'vouchers.transfers.show';
    protected string $pdfView = 'vouchers.transfers.pdf';
    protected string $layoutView = 'vouchers.transfers.partials.voucher-layout';
    protected string $photoField = 'photo';
    protected string $photoToggleField = 'include_photo';

    protected array $validationRules = [
        'trip_type' => ['required', 'in:one_way,round_trip'],
        'confirmation_reference' => ['required', 'string', 'max:100'],
        'booking_date' => ['nullable', 'date'],
        'guest_mobile' => ['nullable', 'string', 'max:40'],
        'pickup_date' => ['required', 'date'],
        'pickup_time' => ['required', 'string', 'max:8'],
        'pickup_location' => ['required', 'string', 'max:200'],
        'dropoff_date' => ['nullable', 'date', 'after_or_equal:pickup_date'],
        'dropoff_time' => ['nullable', 'string', 'max:8'],
        'dropoff_location' => ['required', 'string', 'max:200'],
        'flight_number' => ['nullable', 'string', 'max:40'],
        'flight_date' => ['nullable', 'date'],
        'flight_time' => ['nullable', 'string', 'max:8'],
        'return_pickup_date' => ['nullable', 'date', 'required_if:trip_type,round_trip'],
        'return_pickup_time' => ['nullable', 'string', 'max:8'],
        'return_pickup_location' => ['nullable', 'string', 'max:200', 'required_if:trip_type,round_trip'],
        'return_dropoff_date' => ['nullable', 'date'],
        'return_dropoff_time' => ['nullable', 'string', 'max:8'],
        'return_dropoff_location' => ['nullable', 'string', 'max:200', 'required_if:trip_type,round_trip'],
        'return_flight_number' => ['nullable', 'string', 'max:40'],
        'return_flight_date' => ['nullable', 'date'],
        'return_flight_time' => ['nullable', 'string', 'max:8'],
        'vehicle_type' => ['required', 'string', 'max:120'],
        'vehicle_type_other' => ['nullable', 'string', 'max:120', 'required_if:vehicle_type,Other'],
        'passengers' => ['required', 'integer', 'min:1', 'max:50'],
        'luggage_count' => ['nullable', 'integer', 'min:0', 'max:20'],
        'driver_name' => ['nullable', 'string', 'max:120'],
        'driver_number' => ['nullable', 'string', 'max:40'],
        'special_requests' => ['nullable', 'string', 'max:1000'],
        'terms_text' => ['nullable', 'string', 'max:5000'],
        'include_photo' => ['nullable', 'boolean'],
        'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        'employee_id' => ['nullable', 'exists:employees,id'],
    ];

    protected function resolveCheckInDate(array $validated): string
    {
        return $validated['pickup_date'];
    }

    protected function resolveCheckOutDate(array $validated): string
    {
        return $validated['dropoff_date'] ?? $validated['pickup_date'];
    }

    protected function prepareVoucherData(array $validated, string $voucherNumber, ?string $photoPath, Carbon $checkIn, Carbon $checkOut): array
    {
        return [
            'hotel_name' => '',
            'voucher_number' => $voucherNumber,
            'voucher_type' => 'transfers',
            'voucher_theme' => $validated['voucher_theme'],
            'guest_name' => $validated['guest_name'],
            'confirmation_reference' => $validated['confirmation_reference'],
            'trip_type' => $validated['trip_type'],
            'booking_date' => $validated['booking_date'] ?? null,
            'guest_mobile' => $validated['guest_mobile'] ?? null,
            'check_in_date' => $checkIn,
            'check_out_date' => $validated['dropoff_date'] ? Carbon::parse($validated['dropoff_date']) : $checkOut,
            'check_in_time' => $validated['pickup_time'],
            'check_out_time' => $validated['dropoff_time'] ?? '00:00',
            'nights_count' => $validated['dropoff_date'] ? $checkIn->diffInDays(Carbon::parse($validated['dropoff_date'])) : 0,
            'pickup_location' => $validated['pickup_location'],
            'dropoff_location' => $validated['dropoff_location'],
            'flight_number' => $validated['flight_number'] ?? null,
            'flight_date' => $validated['flight_date'] ?? null,
            'flight_time' => $validated['flight_time'] ?? null,
            'return_pickup_date' => $validated['return_pickup_date'] ?? null,
            'return_pickup_time' => $validated['return_pickup_time'] ?? null,
            'return_pickup_location' => $validated['return_pickup_location'] ?? null,
            'return_dropoff_date' => $validated['return_dropoff_date'] ?? null,
            'return_dropoff_time' => $validated['return_dropoff_time'] ?? null,
            'return_dropoff_location' => $validated['return_dropoff_location'] ?? null,
            'return_flight_number' => $validated['return_flight_number'] ?? null,
            'return_flight_date' => $validated['return_flight_date'] ?? null,
            'return_flight_time' => $validated['return_flight_time'] ?? null,
            'vehicle_type' => $this->resolveOption($validated['vehicle_type'], $validated['vehicle_type_other'] ?? null),
            'passengers' => $validated['passengers'],
            'luggage_count' => $validated['luggage_count'] ?? 0,
            'driver_name' => $validated['driver_name'] ?? null,
            'driver_number' => $validated['driver_number'] ?? null,
            'special_requests' => $validated['special_requests'] ?? null,
            'terms_text' => $validated['terms_text'] ?? null,
            'include_hotel_photo' => (bool) ($validated['include_photo'] ?? false),
            'hotel_photo_path' => $photoPath,
            'employee_id' => $validated['employee_id'] ?: null,
        ];
    }
}