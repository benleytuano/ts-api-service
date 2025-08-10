<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locationsPerDepartment = [
            'Emergency Room' => ['Station 1', 'Station 2'],
            'Outpatient Department' => ['Main Lobby', 'Consultation Room 1'],
            'Cardiology' => ['Echo Room', 'Stress Test Lab'],
            'Neurology' => ['EEG Room', 'Neuro Exam Room'],
            'Radiology' => ['X-Ray Room', 'CT Scan Room', 'MRI Unit'],
            'Pathology' => ['Lab A', 'Lab B'],
            'Pediatrics' => ['Pedia Ward A', 'Pedia Ward B'],
            'Obstetrics and Gynecology' => ['OB Ward', 'Ultrasound Room'],
            'Surgery' => ['Operating Room 1', 'Operating Room 2'],
            'Intensive Care Unit' => ['ICU A', 'ICU B'],
            'Pharmacy' => ['Main Pharmacy', 'Satellite Pharmacy'],
            'Ophthalmology' => ['Eye Exam Room', 'Laser Treatment Room'],
            'Otolaryngology (ENT)' => ['ENT Exam Room 1', 'ENT Procedure Room'],
            'Dental' => ['Dental Room 1', 'Dental Room 2'],
            'Orthopedics' => ['Ortho Consultation Room'],
            'Dermatology' => ['Derma Treatment Room'],
            'Urology' => ['Urology Exam Room'],
            'Nephrology' => ['Dialysis Center'],
            'Pulmonology' => ['Pulmonary Function Lab'],
            'Physical Therapy & Rehabilitation' => ['PT Room 1', 'Rehab Gym'],
        ];

        foreach ($locationsPerDepartment as $departmentName => $locations) {
            $department = Department::where('name', $departmentName)->first();

            if (!$department) {
                continue; // Skip if department doesn't exist
            }

            foreach ($locations as $locationName) {
                Location::create([
                    'department_id' => $department->id,
                    'name' => $locationName,
                ]);
            }
        }
    }
}
