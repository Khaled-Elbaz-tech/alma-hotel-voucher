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
            'Company' => [
                'DAN',
                'Elnahdy',
                'Public Investment Fund (PIF)',
                'ROSHN Group',
                'Ministry of Energy',
                'Dr. Sulaiman Al Habib',
                'Bupa Arabia',
                'Nahdi Medical Company',
                'Petro Rabigh',
                'King Abdullah University of Science and Technology (KAUST)',
                'Matarat Holding',
                'Albaik',
                'NEOM',
                'SAL',
                'Alpha Star Aviation Services',
                'AIC Steel',
                'Abdal Human Resources',
                'Riyadh Holding',
                'ARCO Human Resources Company',
                'Mueen Human Resources Company',
                'Al Mawarid Manpower',
                'Maharah Human Resources Company',
                'Spectra Support Services',
                'Initial Saudi Group',
                'Attieh Medico',
                'Seder Group',
                'SAKHAA',
                'J Event',
                'Prince Mohammad Bin Salman College (MBSC)',
                'Al Balad Development Company',
                'Red Sea Gateway Terminal',
                'ESNAD Saudi Mining Services Company',
                'Balubaid',
                'King Abdullah Economic City (KAEC)',
                'Delta Marketing Company (DMC)',
                'Med Surg Solutions (MSS)',
                'Elm',
                'AQUAT',
                'Nahdi Care Clinics',
                'Folk Maritime',
                'Mawhiba',
                'KAPSARC',
                'Diriyah Company',
                'National Events Center',
                'Arabian Alvest Equipment Services (AES)',
                'Jumeirah',
                'SAJA',
                'Wataniya',
                'Dr. Abdulaziz Alajaji Dental Polyclinic',
                'Jeddah Central Development Company',
                'Other',
            ],
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
