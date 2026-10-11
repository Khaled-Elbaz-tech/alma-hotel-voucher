<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'name' => 'Khaled Elgendi',
                'job_title' => 'Senior Customer Experience Consultant',
                'phone' => '+966 50 000 0000',
                'email' => 'khaled@almatravel.com',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Haifa Ayub',
                'job_title' => 'Customer Experience Consultant',
                'phone' => '+966 50 000 0001',
                'email' => 'haifa@almatravel.com',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($employees as $employee) {
            Employee::updateOrCreate(
                ['email' => $employee['email']],
                $employee
            );
        }
    }
}
