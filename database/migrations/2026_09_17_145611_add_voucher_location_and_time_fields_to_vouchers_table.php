<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('hotel_location_sub', 160)->nullable()->after('hotel_address');
            $table->string('check_in_time', 8)->default('15:00')->after('check_in_date');
            $table->string('check_out_time', 8)->default('12:00')->after('check_out_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['hotel_location_sub', 'check_in_time', 'check_out_time']);
        });
    }
};
