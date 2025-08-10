<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    use HasFactory;
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $departments = [
            'Emergency Room',
            'Outpatient Department',
            'Cardiology',
            'Neurology',
            'Radiology',
            'Pathology',
            'Pediatrics',
            'Obstetrics and Gynecology',
            'Surgery',
            'Intensive Care Unit',
            'Pharmacy',
            'Ophthalmology',
            'Otolaryngology (ENT)',
            'Dental',
            'Orthopedics',
            'Dermatology',
            'Urology',
            'Nephrology',
            'Pulmonology',
            'Physical Therapy & Rehabilitation',
        ];

        foreach ($departments as $name) {
            Department::create(['name' => $name]);
        }
    }
}
