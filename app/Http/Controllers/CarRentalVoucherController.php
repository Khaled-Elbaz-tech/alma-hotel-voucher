<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Voucher;

class CarRentalVoucherController extends BaseVoucherController
{
    protected string $voucherType = 'car-rental';
    protected string $voucherPrefix = 'ALM-CR';
    protected string $createView = 'vouchers.car-rental.create';
    protected string $showView = 'vouchers.car-rental.show';
    protected string $pdfView = 'vouchers.car-rental.pdf';
    protected string $layoutView = 'vouchers.car-rental.partials.voucher-layout';
    protected string $photoField = 'car_photo';
    protected string $photoToggleField = 'include_car_photo';

    protected array $validationRules = [
        'guest_name' => ['required', 'string', 'max:120'],
        'booking_number' => ['nullable', 'string', 'max:100'],
        'contact_number' => ['nullable', 'string', 'max:40'],
        'id_passport_number' => ['nullable', 'string', 'max:100'],
        'company_address' => ['required', 'string', 'max:500'],
        'company_phone' => ['required', 'string', 'max:60'],
        'company_email' => ['nullable', 'email', 'max:160'],
        'confirmation_reference' => ['required', 'string', 'max:100'],
        'pickup_date' => ['required', 'date', 'before:dropoff_date'],
        'dropoff_date' => ['required', 'date', 'after:pickup_date'],
        'pickup_time' => ['required', 'string', 'max:8'],
        'dropoff_time' => ['required', 'string', 'max:8'],
        'pickup_location' => ['required', 'string', 'max:200'],
        'pickup_terminal' => ['nullable', 'string', 'max:100'],
        'dropoff_location' => ['required', 'string', 'max:200'],
        'dropoff_terminal' => ['nullable', 'string', 'max:100'],
        'vehicle_type' => ['required', 'string', 'max:120'],
        'vehicle_type_other' => ['nullable', 'string', 'max:120', 'required_if:vehicle_type,Other'],
        'vehicle_model' => ['nullable', 'string', 'max:120'],
        'insurance_type' => ['nullable', 'string', 'max:120'],
        'insurance_type_other' => ['nullable', 'string', 'max:120', 'required_if:insurance_type,Other'],
        'rental_company' => ['required', 'string', 'max:160'],
        'supplier_reference' => ['nullable', 'string', 'max:100'],
        'important_notes' => ['nullable', 'string', 'max:1000'],
        'include_car_photo' => ['nullable', 'boolean'],
        'car_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        'employee_id' => ['nullable', 'exists:employees,id'],
    ];

    protected function resolveCheckInDate(array $validated): string
    {
        return $validated['pickup_date'];
    }

    protected function resolveCheckOutDate(array $validated): string
    {
        return $validated['dropoff_date'];
    }

    protected function prepareVoucherData(array $validated, string $voucherNumber, ?string $photoPath, Carbon $checkIn, Carbon $checkOut): array
    {
        return [
            'voucher_number' => $voucherNumber,
            'voucher_type' => 'car-rental',
            'voucher_theme' => $validated['voucher_theme'],
            'guest_name' => $validated['guest_name'],
            'booking_number' => $validated['booking_number'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'id_passport_number' => $validated['id_passport_number'] ?? null,
            'company_name' => $validated['rental_company'],
            'company_address' => $validated['company_address'],
            'company_phone' => $validated['company_phone'],
            'company_email' => $validated['company_email'] ?? null,
            'confirmation_reference' => $validated['confirmation_reference'],
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'check_in_time' => $validated['pickup_time'],
            'check_out_time' => $validated['dropoff_time'],
            'nights_count' => $checkIn->diffInDays($checkOut),
            'pickup_location' => $validated['pickup_location'],
            'pickup_terminal' => $validated['pickup_terminal'] ?? null,
            'dropoff_location' => $validated['dropoff_location'],
            'dropoff_terminal' => $validated['dropoff_terminal'] ?? null,
            'vehicle_type' => $this->resolveOption($validated['vehicle_type'], $validated['vehicle_type_other'] ?? null),
            'vehicle_model' => $validated['vehicle_model'] ?? null,
            'insurance_type' => $this->resolveOption($validated['insurance_type'], $validated['insurance_type_other'] ?? null),
            'rental_company' => $validated['rental_company'],
            'supplier_reference' => $validated['supplier_reference'] ?? null,
            'important_notes' => $validated['important_notes'] ?? null,
            'include_hotel_photo' => (bool) ($validated['include_car_photo'] ?? false),
            'hotel_photo_path' => $photoPath,
            'employee_id' => $validated['employee_id'] ?: null,
        ];
    }
}