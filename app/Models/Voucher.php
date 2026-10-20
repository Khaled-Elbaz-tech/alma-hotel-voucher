<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'rental_duration',
        'flight_arrival_date',
        'flight_airport',
        'trip_type',
        'booking_date',
        'guest_mobile',
        'driver_name',
        'driver_number',
        'terms_text',
        'flight_date',
        'flight_time',
        'return_pickup_date',
        'return_pickup_time',
        'return_pickup_location',
        'return_dropoff_date',
        'return_dropoff_time',
        'return_dropoff_location',
        'return_flight_number',
        'return_flight_date',
        'return_flight_time',
        'service_meet_assist',
        'service_private_car',
        'service_immigration',
        'user_id',
        'nights_count',
        'meal_plan',
        'occupancy',
        'room_type',
        'cancellation_policy',
        'vip_lounge_access',
        'client_type',
        'client_company',
        'supplier',
        'share_token',
        'share_expires_at',
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
            'service_meet_assist' => 'boolean',
            'service_private_car' => 'boolean',
            'service_immigration' => 'boolean',
            'flight_arrival_date' => 'date',
            'booking_date' => 'date',
            'flight_date' => 'date',
            'return_pickup_date' => 'date',
            'return_dropoff_date' => 'date',
            'return_flight_date' => 'date',
            'share_expires_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public const TYPES = [
        'hotel' => 'Hotel',
        'car-rental' => 'Car Rental',
        'transfers' => 'Transfers',
        'vip-meet-assist' => 'VIP Meet & Assist',
    ];

    public function ensureShareToken(): string
    {
        if (! $this->share_token || ($this->share_expires_at && $this->share_expires_at->isPast())) {
            $this->forceFill([
                'share_token' => \Illuminate\Support\Str::random(48),
                'share_expires_at' => now()->addDays(30),
            ])->save();
        }

        return $this->share_token;
    }

    public function shareValid(): bool
    {
        return (bool) $this->share_token
            && (! $this->share_expires_at || $this->share_expires_at->isFuture());
    }

    public function shareUrl(): string
    {
        return route('voucher.share', $this->ensureShareToken());
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->voucher_type] ?? (string) $this->voucher_type;
    }

    public function publicShowRoute(): ?string
    {
        return match ($this->voucher_type) {
            'hotel' => 'hotel-voucher.show',
            'car-rental' => 'car-rental-voucher.show',
            'transfers' => 'transfers-voucher.show',
            'vip-meet-assist' => 'vip-meet-assist-voucher.show',
            default => null,
        };
    }

    public function publicPdfRoute(): ?string
    {
        return match ($this->voucher_type) {
            'hotel' => 'hotel-voucher.pdf',
            'car-rental' => 'car-rental-voucher.pdf',
            'transfers' => 'transfers-voucher.pdf',
            'vip-meet-assist' => 'vip-meet-assist-voucher.pdf',
            default => null,
        };
    }

    /** Compact one-line summary used by admin tables. Null-safe. */
    /**
     * Default (fixed) terms text per voucher type, pre-filled in the form's
     * Terms section. Staff can edit it per booking; empty values fall back
     * to these defaults when rendering the voucher.
     */
    public static function defaultTerms(string $voucherType): string
    {
        return match ($voucherType) {
            'car-rental' => "1. The car rental price does not include traffic violations or any other vehicle damage during the rental period and are fully the responsibility of the guest.\n2. If any accident occurs, the driver must share the complete documentation with the official authorities and insurance companies.\n3. Windshield cracks or chips (must not be related to criminal cases) are payable by the driver only.\n4. Tire punctures and bulges are not covered (in case of traffic violations, criminal cases or reckless driving).\n5. Natural disasters (a Civil Defense report is required).\n6. Fading or erosion of the vehicle's front exterior (dust, sandstorms or similar damage) is not covered.",
            'transfers' => "1. Cancellation and extra charges policy applies.\n2. Smoking and food are strictly prohibited inside the vehicle — violations incur cleaning/penalty charges.\n3. Extra en-route stops not in the confirmed itinerary are charged separately.\n4. Waiting charges: 150 SAR per hour.\n5. 100% charge within 24 hours or no-show; cancellation after confirmation counts as no-show.\n6. Grace period: 30 min (domestic airport) / 15 min (other locations); extra waiting billed per 30 minutes.",
            default => '',
        };
    }

    public function summaryLine(): string
    {        $line = match ($this->voucher_type) {
            'hotel' => collect([
                $this->hotel_name,
                trim(($this->check_in_date?->format('d M') ?? '').($this->check_out_date ? ' → '.$this->check_out_date->format('d M Y') : '')),
            ])->filter()->implode(' · '),
            'car-rental' => collect([
                trim(($this->vehicle_type ?? '').($this->vehicle_model ? ' ('.$this->vehicle_model.')' : '')),
                trim(($this->pickup_location ?? '').' → '.($this->dropoff_location ?? ''), ' →'),
            ])->filter()->implode(' · '),
            'transfers' => collect([
                $this->pickup_location,
                $this->dropoff_location,
            ])->filter()->implode(' → '),
            'vip-meet-assist' => collect([
                $this->service_type,
                trim(trim((string) $this->airline).' '.trim((string) $this->flight_number)),
            ])->filter()->implode(' · '),
            default => '',
        };

        return $line !== '' ? $line : '—';
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
