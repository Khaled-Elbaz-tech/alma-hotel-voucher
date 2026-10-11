<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'address',
        'brand_primary_color',
        'brand_dark_red',
        'brand_gold',
        'brand_surface_light',
        'brand_surface_dark',
        'voucher_theme',
        'tagline',
        'logo_path',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function logoUrl(): ?string
    {
        return $this->logo_path ? asset('storage/'.$this->logo_path) : null;
    }
}
