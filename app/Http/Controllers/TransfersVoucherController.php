<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Voucher;

class TransfersVoucherController extends BaseVoucherController
{
    protected string $voucherType = 'transfers';
    protected string $voucherPrefix = 'ALM-TR';
    protected string $createView = 'vouchers.transfers.create';
    protected string $showView = 'vouchers.transfers.show';
    protected string $pdfView = 'vouchers.transfers.pdf';
    protected string $layoutView = 'vouchers.transfers.partials.voucher-layout';
    protected string $photoField = 'photo';
    protected string $photoToggleField = 'include_photo';

    protected array $validationRules = [
        'provider_name' => ['required', 'string', 'max:160'],
        'provider_address' => ['nullable', 'string', 'max:500'],
        'provider_phone' => ['required', 'string', 'max:60'],
        'provider_email' => ['nullable', 'email', 'max:160'],
        'confirmation_reference' => ['required', 'string', 'max:100'],
        'pickup_date' => ['required', 'date', 'before:dropoff_date'],
        'dropoff_date' => ['nullable', 'date', 'after_or_equal:pickup_date'],
        'pickup_time' => ['required', 'string', 'max:8'],
        'dropoff_time' => ['nullable', 'string', 'max:8'],
        'pickup_location' => ['required', 'string', 'max:200'],
        'dropoff_location' => ['required', 'string', 'max:200'],
        'flight_number' => ['nullable', 'string', 'max:40'],
        'vehicle_type' => ['required', 'string', 'max:120'],
        'vehicle_type_other' => ['nullable', 'string', 'max:120', 'required_if:vehicle_type,Other'],
        'passengers' => ['required', 'integer', 'min:1', 'max:50'],
        'luggage_count' => ['nullable', 'integer', 'min:0', 'max:20'],
        'meet_greet' => ['nullable', 'boolean'],
        'special_requests' => ['nullable', 'string', 'max:1000'],
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
            'voucher_number' => $voucherNumber,
            'voucher_type' => 'transfers',
            'voucher_theme' => $validated['voucher_theme'],
            'guest_name' => $validated['guest_name'],
            'company_name' => $validated['provider_name'],
            'company_address' => $validated['provider_address'] ?? null,
            'company_phone' => $validated['provider_phone'],
            'company_email' => $validated['provider_email'] ?? null,
            'confirmation_reference' => $validated['confirmation_reference'],
            'check_in_date' => $checkIn,
            'check_out_date' => $validated['dropoff_date'] ? Carbon::parse($validated['dropoff_date']) : $checkOut,
            'check_in_time' => $validated['pickup_time'],
            'check_out_time' => $validated['dropoff_time'] ?? '00:00',
            'nights_count' => $validated['dropoff_date'] ? $checkIn->diffInDays(Carbon::parse($validated['dropoff_date'])) : 0,
            'pickup_location' => $validated['pickup_location'],
            'dropoff_location' => $validated['dropoff_location'],
            'flight_number' => $validated['flight_number'] ?? null,
            'vehicle_type' => $this->resolveOption($validated['vehicle_type'], $validated['vehicle_type_other'] ?? null),
            'passengers' => $validated['passengers'],
            'luggage_count' => $validated['luggage_count'] ?? 0,
            'meet_greet' => (bool) ($validated['meet_greet'] ?? false),
            'special_requests' => $validated['special_requests'] ?? null,
            'include_hotel_photo' => (bool) ($validated['include_photo'] ?? false),
            'hotel_photo_path' => $photoPath,
            'employee_id' => $validated['employee_id'] ?: null,
        ];
    }
}