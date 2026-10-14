<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Voucher;

class HotelVoucherController extends BaseVoucherController
{
    protected string $voucherType = 'hotel';
    protected string $voucherPrefix = 'ALMA-HV';
    protected string $createView = 'vouchers.hotel.create';
    protected string $showView = 'vouchers.hotel.show';
    protected string $pdfView = 'vouchers.hotel.pdf-simple';
    protected string $layoutView = 'vouchers.hotel.partials.voucher-layout';
    protected string $photoField = 'hotel_photo';
    protected string $photoToggleField = 'include_hotel_photo';

    protected array $validationRules = [
        'hotel_name' => ['required', 'string', 'max:160'],
        'hotel_address' => ['required', 'string', 'max:500'],
        'hotel_location_sub' => ['nullable', 'string', 'max:160'],
        'hotel_phone' => ['required', 'string', 'max:60'],
        'occupancy' => ['nullable', 'string', 'max:120'],
        'confirmation_reference' => ['required', 'string', 'max:100'],
        'check_in_date' => ['required', 'date', 'before:check_out_date'],
        'check_out_date' => ['required', 'date', 'after:check_in_date'],
        'check_in_time' => ['required', 'string', 'max:8'],
        'check_out_time' => ['required', 'string', 'max:8'],
        'special_requests' => ['nullable', 'string', 'max:1000'],
        'meal_plan' => ['required', 'string', 'max:120'],
        'meal_plan_other' => ['nullable', 'string', 'max:120', 'required_if:meal_plan,Other'],
        'room_type' => ['required', 'string', 'max:120'],
        'room_type_other' => ['nullable', 'string', 'max:120', 'required_if:room_type,Other'],
        'cancellation_policy' => ['required', 'string'],
        'cancellation_policy_other' => ['nullable', 'string', 'max:500', 'required_if:cancellation_policy,Other'],
        'include_hotel_photo' => ['nullable', 'boolean'],
        'hotel_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        'employee_id' => ['nullable', 'exists:employees,id'],
    ];

    protected function prepareVoucherData(array $validated, string $voucherNumber, ?string $photoPath, Carbon $checkIn, Carbon $checkOut): array
    {
        return [
            'voucher_number' => $voucherNumber,
            'voucher_type' => 'hotel',
            'voucher_theme' => $validated['voucher_theme'],
            'guest_name' => $validated['guest_name'],
            'hotel_name' => $validated['hotel_name'],
            'hotel_address' => $validated['hotel_address'],
            'hotel_location_sub' => $validated['hotel_location_sub'] ?? null,
            'hotel_phone' => $validated['hotel_phone'],
            'confirmation_reference' => $validated['confirmation_reference'],
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'check_in_time' => $validated['check_in_time'],
            'check_out_time' => $validated['check_out_time'],
            'nights_count' => $checkIn->diffInDays($checkOut),
            'occupancy' => $validated['occupancy'] ?? '1 Room | 2 Adults',
            'meal_plan' => $this->resolveOption($validated['meal_plan'], $validated['meal_plan_other'] ?? null),
            'room_type' => $this->resolveOption($validated['room_type'], $validated['room_type_other'] ?? null),
            'cancellation_policy' => $this->resolveOption($validated['cancellation_policy'], $validated['cancellation_policy_other'] ?? null),
            'special_requests' => $validated['special_requests'] ?? null,
            'include_hotel_photo' => (bool) ($validated['include_hotel_photo'] ?? false),
            'hotel_photo_path' => $photoPath,
            'employee_id' => $validated['employee_id'] ?: null,
        ];
    }
}