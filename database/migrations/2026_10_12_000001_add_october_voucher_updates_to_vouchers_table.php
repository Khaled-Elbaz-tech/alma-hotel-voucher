<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Idempotent: only add columns that don't exist yet
        $columns = [
            'rental_duration' => fn (Blueprint $t) => $t->unsignedSmallInteger('rental_duration')->nullable(),
            'branch_timing' => fn (Blueprint $t) => $t->string('branch_timing', 120)->nullable(),
            'flight_arrival_date' => fn (Blueprint $t) => $t->date('flight_arrival_date')->nullable(),
            'flight_airport' => fn (Blueprint $t) => $t->string('flight_airport', 160)->nullable(),
            'trip_type' => fn (Blueprint $t) => $t->string('trip_type', 20)->nullable(),
            'booking_date' => fn (Blueprint $t) => $t->date('booking_date')->nullable(),
            'guest_mobile' => fn (Blueprint $t) => $t->string('guest_mobile', 40)->nullable(),
            'driver_name' => fn (Blueprint $t) => $t->string('driver_name', 120)->nullable(),
            'driver_number' => fn (Blueprint $t) => $t->string('driver_number', 40)->nullable(),
            'flight_date' => fn (Blueprint $t) => $t->date('flight_date')->nullable(),
            'flight_time' => fn (Blueprint $t) => $t->string('flight_time', 8)->nullable(),
            'return_pickup_date' => fn (Blueprint $t) => $t->date('return_pickup_date')->nullable(),
            'return_pickup_time' => fn (Blueprint $t) => $t->string('return_pickup_time', 8)->nullable(),
            'return_pickup_location' => fn (Blueprint $t) => $t->string('return_pickup_location', 200)->nullable(),
            'return_dropoff_date' => fn (Blueprint $t) => $t->date('return_dropoff_date')->nullable(),
            'return_dropoff_time' => fn (Blueprint $t) => $t->string('return_dropoff_time', 8)->nullable(),
            'return_dropoff_location' => fn (Blueprint $t) => $t->string('return_dropoff_location', 200)->nullable(),
            'return_flight_number' => fn (Blueprint $t) => $t->string('return_flight_number', 40)->nullable(),
            'return_flight_date' => fn (Blueprint $t) => $t->date('return_flight_date')->nullable(),
            'return_flight_time' => fn (Blueprint $t) => $t->string('return_flight_time', 8)->nullable(),
            'service_meet_assist' => fn (Blueprint $t) => $t->boolean('service_meet_assist')->default(false),
            'service_private_car' => fn (Blueprint $t) => $t->boolean('service_private_car')->default(false),
            'service_immigration' => fn (Blueprint $t) => $t->boolean('service_immigration')->default(false),
            'terms_text' => fn (Blueprint $t) => $t->text('terms_text')->nullable(),
        ];

        foreach ($columns as $name => $add) {
            if (! Schema::hasColumn('vouchers', $name)) {
                Schema::table('vouchers', $add);
            }
        }
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
