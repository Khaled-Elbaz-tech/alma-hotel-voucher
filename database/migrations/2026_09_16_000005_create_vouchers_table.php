<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number', 30)->unique();
            $table->string('guest_name', 120);
            $table->string('hotel_name', 160);
            $table->text('hotel_address');
            $table->string('confirmation_reference', 100);
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->unsignedSmallInteger('nights_count');
            $table->string('meal_plan', 120);
            $table->string('room_type', 120);
            $table->string('payment_method', 120);
            $table->text('cancellation_policy');
            $table->boolean('include_hotel_photo')->default(false);
            $table->string('hotel_photo_path')->nullable();
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->string('pdf_path')->nullable();
            $table->string('png_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
