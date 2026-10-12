<?php

namespace Database\Seeders;

use App\Models\LookupCategory;
use Illuminate\Database\Seeder;

class CarRentalLookupSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'VehicleType' => ['Economy', 'Compact', 'Intermediate', 'Standard', 'Full Size', 'Premium', 'Luxury', 'SUV', 'Minivan', 'Convertible', 'Other'],
            'CarTransmission' => ['Automatic', 'Manual'],
            'CarInclusion' => ['Unlimited Mileage', 'Fuel Policy', 'Insurance / Coverage', 'Roadside Assistance', 'Additional Driver', 'Child Seat', 'GPS / Navigation', 'Wi-Fi', 'Other'],
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