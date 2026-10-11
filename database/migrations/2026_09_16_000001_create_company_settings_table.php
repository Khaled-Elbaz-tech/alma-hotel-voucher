<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->text('address');
            $table->string('brand_primary_color', 7);
            $table->string('brand_dark_red', 7);
            $table->string('brand_gold', 7);
            $table->string('tagline')->default('Your Journey, OUR COMMITMENT');
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
