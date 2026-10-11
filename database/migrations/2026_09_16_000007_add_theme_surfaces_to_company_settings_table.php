<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('brand_surface_light', 7)->default('#FFFFFF')->after('brand_gold');
            $table->string('brand_surface_dark', 7)->default('#1A202C')->after('brand_surface_light');
        });
    }

    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn(['brand_surface_light', 'brand_surface_dark']);
        });
    }
};
