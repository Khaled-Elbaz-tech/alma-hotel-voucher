<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_number',
        'voucher_type',
        'voucher_theme',
        'guest_name',
        'booking_number',
        'contact_number',
        'id_passport_number',
        'hotel_name',
        'hotel_address',
        'hotel_location_sub',
        'hotel_phone',
        'confirmation_reference',
        'check_in_date',
        'check_out_date',
        'check_in_time',
        'check_out_time',
        'pickup_location',
        'pickup_terminal',
        'dropoff_location',
        'dropoff_terminal',
        'vehicle_type',
        'vehicle_model',
        'insurance_type',
        'rental_company',
        'supplier_reference',
        'important_notes',
        'special_requests',
        'include_hotel_photo',
        'hotel_photo_path',
        'employee_id',
        'pdf_path',
        'png_path',
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'terminal',
        'flight_number',
        'flight_arrival_time',
        'airline',
        'pickup_time',
        'dropoff_time',
        'passengers',
        'luggage_count',
        'service_type',
        'meeting_point',
        'meeting_time',
        'representative_name',
        'representative_contact',
        'fast_track',
        'porter_service',
        'wheelchair',
        'private_assistance',
        'family_assistance',
        'elderly_assistance',
        'child_assistance',
        'vip_service_notes',
    ];

    protected function casts(): array
    {
        return [
            'check_in_date' => 'date',
            'check_out_date' => 'date',
            'include_hotel_photo' => 'boolean',
            'wheelchair' => 'boolean',
            'private_assistance' => 'boolean',
            'family_assistance' => 'boolean',
            'elderly_assistance' => 'boolean',
            'child_assistance' => 'boolean',
            'fast_track' => 'boolean',
            'porter_service' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function hotelPhotoUrl(): ?string
    {
        return $this->hotel_photo_path ? asset('storage/'.$this->hotel_photo_path) : null;
    }

    public function photoUrl(): ?string
    {
        return $this->hotel_photo_path ? asset('storage/'.$this->hotel_photo_path) : null;
    }

    public function pdfUrl(): ?string
    {
        return $this->pdf_path ? asset('storage/'.$this->pdf_path) : null;
    }

    public function pngUrl(): ?string
    {
        return $this->png_path ? asset('storage/'.$this->png_path) : null;
    }
}
