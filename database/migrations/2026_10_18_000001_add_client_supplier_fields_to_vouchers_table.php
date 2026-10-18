<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('client_type', 10)->nullable()->after('voucher_theme'); // credit|cash
            $table->string('client_company', 160)->nullable()->after('client_type');
            $table->string('supplier', 160)->nullable()->after('client_company');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['client_type', 'client_company', 'supplier']);
        });
    }
};
