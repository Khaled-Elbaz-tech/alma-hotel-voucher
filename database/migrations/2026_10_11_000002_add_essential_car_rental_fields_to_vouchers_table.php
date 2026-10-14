<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            // Rental Company
            $table->string('rental_company', 160)->nullable()->after('vehicle_type');

            // Important Notes (single section)
            $table->text('important_notes')->nullable()->after('rental_company');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn([
                'rental_company',
                'important_notes',
            ]);
        });
    }
};
