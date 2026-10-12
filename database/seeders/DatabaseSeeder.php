<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CompanySettingSeeder::class,
            AdminUserSeeder::class,
            EmployeeSeeder::class,
            LookupSeeder::class,
            CarRentalLookupSeeder::class,
            VipLookupSeeder::class,
        ]);
    }
}
