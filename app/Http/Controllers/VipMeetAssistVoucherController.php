<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Voucher;

class VipMeetAssistVoucherController extends BaseVoucherController
{
    protected string $voucherType = 'vip-meet-assist';
    protected string $voucherPrefix = 'ALMA-VIP';
    protected string $createView = 'vouchers.vip-meet-assist.create';
    protected string $showView = 'vouchers.vip-meet-assist.show';
    protected string $pdfView = 'vouchers.vip-meet-assist.pdf-simple';
    protected string $layoutView = 'vouchers.vip-meet-assist.partials.voucher-layout';
    protected string $photoField = 'photo';
    protected string $photoToggleField = 'include_photo';

    protected array $validationRules = [
        'agent_name' => ['required', 'string', 'max:160'],
        'agent_company' => ['nullable', 'string', 'max:160'],
        'agent_phone' => ['required', 'string', 'max:60'],
        'agent_email' => ['nullable', 'email', 'max:160'],
        'confirmation_reference' => ['required', 'string', 'max:100'],
        'service_date' => ['required', 'date'],
        'service_time' => ['required', 'string', 'max:8'],
        'meeting_point' => ['required', 'string', 'max:200'],
        'terminal' => ['nullable', 'string', 'max:50'],
        'flight_number' => ['required', 'string', 'max:40'],
        'flight_arrival_time' => ['required', 'string', 'max:8'],
        'service_type' => ['required', 'string', 'max:120'],
        'service_type_other' => ['nullable', 'string', 'max:120', 'required_if:service_type,Other'],
        'passengers' => ['required', 'integer', 'min:1', 'max:20'],
        'service_meet_assist' => ['nullable', 'boolean'],
        'service_private_car' => ['nullable', 'boolean'],
        'vip_lounge_access' => ['nullable', 'boolean'],
        'fast_track' => ['nullable', 'boolean'],
        'service_immigration' => ['nullable', 'boolean'],
        'special_requests' => ['nullable', 'string', 'max:1000'],
        'include_photo' => ['nullable', 'boolean'],
        'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        'employee_id' => ['nullable', 'exists:employees,id'],
    ];

    protected function resolveCheckInDate(array $validated): string
    {
        return $validated['service_date'];
    }

    protected function resolveCheckOutDate(array $validated): string
    {
        return $validated['service_date'];
    }

    protected function prepareVoucherData(array $validated, string $voucherNumber, ?string $photoPath, Carbon $checkIn, Carbon $checkOut): array
    {
        return [
            'hotel_name' => '',
            'hotel_address' => '',
            'meal_plan' => '',
            'room_type' => '',
            'payment_method' => '',
            'cancellation_policy' => '',
            'voucher_number' => $voucherNumber,
            'voucher_type' => 'vip-meet-assist',
            'voucher_theme' => $validated['voucher_theme'],
            'guest_name' => $validated['guest_name'],
            'company_name' => $validated['agent_name'],
            'company_address' => $validated['agent_company'] ?? null,
            'company_phone' => $validated['agent_phone'],
            'company_email' => $validated['agent_email'] ?? null,
            'confirmation_reference' => $validated['confirmation_reference'],
            'check_in_date' => $checkIn,
            'check_out_date' => $checkIn,
            'check_in_time' => $validated['service_time'],
            'check_out_time' => '00:00',
            'nights_count' => 0,
            'meeting_point' => $validated['meeting_point'],
            'terminal' => $validated['terminal'] ?? null,
            'flight_number' => $validated['flight_number'],
            'flight_arrival_time' => $validated['flight_arrival_time'],
            'service_type' => $this->resolveOption($validated['service_type'], $validated['service_type_other'] ?? null),
            'passengers' => $validated['passengers'],
            'service_meet_assist' => (bool) ($validated['service_meet_assist'] ?? false),
            'service_private_car' => (bool) ($validated['service_private_car'] ?? false),
            'vip_lounge_access' => (bool) ($validated['vip_lounge_access'] ?? false),
            'fast_track' => (bool) ($validated['fast_track'] ?? false),
            'service_immigration' => (bool) ($validated['service_immigration'] ?? false),
            'special_requests' => $validated['special_requests'] ?? null,
            'include_hotel_photo' => (bool) ($validated['include_photo'] ?? false),
            'hotel_photo_path' => $photoPath,
            'employee_id' => $validated['employee_id'] ?: null,
        ];
    }
}