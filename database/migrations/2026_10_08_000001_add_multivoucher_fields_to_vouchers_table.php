<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('voucher_type', 30)->default('hotel')->after('voucher_number');

            // Car Rental fields
            $table->string('company_name', 160)->nullable()->after('hotel_phone');
            $table->text('company_address')->nullable()->after('company_name');
            $table->string('company_phone', 60)->nullable()->after('company_address');
            $table->string('company_email', 160)->nullable()->after('company_phone');
            $table->string('pickup_location', 200)->nullable()->after('special_requests');
            $table->string('dropoff_location', 200)->nullable()->after('pickup_location');
            $table->string('car_category', 120)->nullable()->after('dropoff_location');
            $table->string('transmission', 20)->nullable()->after('car_category');
            $table->string('fuel_policy', 120)->nullable()->after('transmission');
            $table->string('insurance_type', 120)->nullable()->after('fuel_policy');
            $table->string('driver_name', 120)->nullable()->after('insurance_type');
            $table->string('driver_license', 60)->nullable()->after('driver_name');

            // Transfers & VIP fields
            $table->string('flight_number', 40)->nullable()->after('dropoff_location');
            $table->string('vehicle_type', 120)->nullable()->after('flight_number');
            $table->unsignedSmallInteger('passengers')->nullable()->after('vehicle_type');
            $table->unsignedSmallInteger('luggage_count')->nullable()->after('passengers');
            $table->boolean('meet_greet')->default(false)->after('luggage_count');
            $table->string('meeting_point', 200)->nullable()->after('nights_count');
            $table->string('terminal', 50)->nullable()->after('meeting_point');
            $table->string('flight_arrival_time', 8)->nullable()->after('terminal');
            $table->string('service_type', 120)->nullable()->after('flight_arrival_time');
            $table->boolean('vip_lounge_access')->default(false)->after('service_type');
            $table->boolean('fast_track')->default(false)->after('vip_lounge_access');
            $table->boolean('porter_service')->default(false)->after('fast_track');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn([
                'voucher_type', 'company_name', 'company_address', 'company_phone',
                'company_email', 'pickup_location', 'dropoff_location', 'car_category',
                'transmission', 'fuel_policy', 'insurance_type', 'driver_name',
                'driver_license', 'flight_number', 'vehicle_type', 'passengers',
                'luggage_count', 'meet_greet', 'meeting_point', 'terminal',
                'flight_arrival_time', 'service_type', 'vip_lounge_access',
                'fast_track', 'porter_service',
            ]);
        });
    }
};
