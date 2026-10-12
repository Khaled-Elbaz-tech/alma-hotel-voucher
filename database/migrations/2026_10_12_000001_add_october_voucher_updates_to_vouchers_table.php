<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            // Car Rental: manual duration (days), branch timing, flight details
            $table->unsignedSmallInteger('rental_duration')->nullable();
            $table->string('branch_timing', 120)->nullable();
            $table->date('flight_arrival_date')->nullable();
            $table->string('flight_airport', 160)->nullable();

            // Transfers: trip type, booking info, driver, expanded flight details, return leg
            $table->string('trip_type', 20)->nullable();
            $table->date('booking_date')->nullable();
            $table->string('guest_mobile', 40)->nullable();
            $table->string('driver_name', 120)->nullable();
            $table->string('driver_number', 40)->nullable();
            $table->date('flight_date')->nullable();
            $table->string('flight_time', 8)->nullable();
            $table->date('return_pickup_date')->nullable();
            $table->string('return_pickup_time', 8)->nullable();
            $table->string('return_pickup_location', 200)->nullable();
            $table->date('return_dropoff_date')->nullable();
            $table->string('return_dropoff_time', 8)->nullable();
            $table->string('return_dropoff_location', 200)->nullable();
            $table->string('return_flight_number', 40)->nullable();
            $table->date('return_flight_date')->nullable();
            $table->string('return_flight_time', 8)->nullable();

            // VIP Meet & Assist: fixed 5-service checklist (fast_track + vip_lounge_access already exist)
            $table->boolean('service_meet_assist')->default(false);
            $table->boolean('service_private_car')->default(false);
            $table->boolean('service_immigration')->default(false);

            // Editable terms (per-voucher copy of the fixed default terms)
            $table->text('terms_text')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn([
                'rental_duration', 'branch_timing', 'flight_arrival_date', 'flight_airport',
                'trip_type', 'booking_date', 'guest_mobile', 'driver_name', 'driver_number',
                'flight_date', 'flight_time',
                'return_pickup_date', 'return_pickup_time', 'return_pickup_location',
                'return_dropoff_date', 'return_dropoff_time', 'return_dropoff_location',
                'return_flight_number', 'return_flight_date', 'return_flight_time',
                'service_meet_assist', 'service_private_car', 'service_immigration',
                'terms_text',
            ]);
        });
    }
};
