<?php

namespace Database\Seeders;

use App\Models\LookupCategory;
use Illuminate\Database\Seeder;

class LookupSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'MealPlan' => ['Breakfast Included', 'Half Board', 'Full Board', 'All Inclusive', 'Room Only', 'Other'],
            'RoomType' => ['Standard Room', 'Deluxe Room', 'Executive Room', 'Suite', 'Connecting Rooms', 'Other'],
            'PaymentMethod' => ['Direct Bill', 'Credit Card', 'Debit Card', 'Cash', 'Bank Transfer', 'Other'],
            'CancellationPolicy' => ['Free Cancellation', 'Non-Refundable', 'Flexible', 'Partial Refund', 'Other'],
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
