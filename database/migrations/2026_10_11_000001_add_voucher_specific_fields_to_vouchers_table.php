<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            // Car Rental specific fields
            $table->text('terms_conditions')->nullable();
            $table->text('insurance_notes')->nullable();
            $table->text('driver_responsibility_notes')->nullable();
            $table->text('special_instructions')->nullable();

            // Transfers specific fields
            $table->string('airline', 80)->nullable();
            $table->string('service_type_detail', 120)->nullable();
            $table->string('driver_phone', 40)->nullable();
            $table->text('waiting_charges')->nullable();
            $table->text('en_route_stop_charges')->nullable();
            $table->text('no_show_policy')->nullable();

            // VIP Meet & Assist specific fields
            $table->string('arrival_departure', 20)->nullable();
            $table->string('vip_package', 80)->nullable();
            $table->string('representative_name', 120)->nullable();
            $table->string('representative_contact', 60)->nullable();
            $table->string('meeting_time', 8)->nullable();
            $table->boolean('wheelchair')->default(false);
            $table->boolean('private_assistance')->default(false);
            $table->boolean('family_assistance')->default(false);
            $table->boolean('elderly_assistance')->default(false);
            $table->boolean('child_assistance')->default(false);
            $table->text('vip_service_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn([
                'terms_conditions', 'insurance_notes', 'driver_responsibility_notes',
                'special_instructions', 'airline', 'service_type_detail', 'driver_phone',
                'waiting_charges', 'en_route_stop_charges', 'no_show_policy',
                'arrival_departure', 'vip_package', 'representative_name',
                'representative_contact', 'meeting_time', 'wheelchair',
                'private_assistance', 'family_assistance', 'elderly_assistance',
                'child_assistance', 'vip_service_notes',
            ]);
        });
    }
};
