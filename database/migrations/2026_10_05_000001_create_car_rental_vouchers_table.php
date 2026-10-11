<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_rental_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number', 30)->unique();

            // Booking details
            $table->string('driver_name', 120);
            $table->string('confirmation_number', 100);
            $table->string('supplier_reference', 100)->nullable();
            $table->string('rental_company', 160);

            // Rental details
            $table->date('pick_up_date');
            $table->string('pick_up_time', 8);
            $table->string('pick_up_location', 255);
            $table->date('drop_off_date');
            $table->string('drop_off_time', 8);
            $table->string('drop_off_location', 255);
            $table->unsignedSmallInteger('rental_duration');

            // Vehicle details
            $table->string('vehicle_category', 120);
            $table->string('vehicle_model', 120)->nullable();
            $table->string('transmission', 60);
            $table->unsignedSmallInteger('passenger_capacity');
            $table->unsignedSmallInteger('luggage_capacity');

            // Flexible options and information
            $table->json('inclusions')->nullable();
            $table->text('rental_terms')->nullable();
            $table->text('special_instructions')->nullable();
            $table->text('cancellation_policy');

            $table->boolean('include_vehicle_photo')->default(false);
            $table->string('vehicle_photo_path')->nullable();

            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->string('pdf_path')->nullable();
            $table->string('png_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_rental_vouchers');
    }
};