<?php

namespace Database\Seeders;

use App\Models\LookupCategory;
use Illuminate\Database\Seeder;

class VipLookupSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'ServiceType' => ['Arrival Meet & Greet', 'Departure Meet & Assist', 'Transit / Connection Assist', 'VIP Lounge Access'],
        ];

        foreach ($categories as $name => $values) {
            $category = LookupCategory::updateOrCreate(
                ['slug' => strtolower($name)],
                ['name' => $name, 'sort_order' => array_search($name, array_keys($categories)) + 1]
            );

            foreach ($values as $index => $value) {
                $category->values()->updateOrCreate(
                    ['value' => $value],
                    ['sort_order' => $index + 1, 'is_active' => true]
                );
            }
        }
    }
}
