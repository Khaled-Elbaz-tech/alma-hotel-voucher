<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('supplier_reference', 100)->nullable()->after('confirmation_reference');
            $table->string('hotel_phone', 60)->nullable()->after('hotel_address');
            $table->string('occupancy', 120)->nullable()->after('nights_count');
            $table->text('special_requests')->nullable()->after('cancellation_policy');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['supplier_reference', 'hotel_phone', 'occupancy', 'special_requests']);
        });
    }
};
