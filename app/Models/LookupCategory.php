<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookupCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function values()
    {
        return $this->hasMany(LookupValue::class, 'lookup_category_id')->orderBy('sort_order')->orderBy('value');
    }

    public function activeValues()
    {
        return $this->hasMany(LookupValue::class, 'lookup_category_id')->where('is_active', true)->orderBy('sort_order')->orderBy('value');
    }
}
