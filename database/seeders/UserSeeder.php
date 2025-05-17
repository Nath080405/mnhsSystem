<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Section;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin users if they don't exist
        $adminUser1 = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'last_name' => 'Matanog',
                'first_name' => 'Mae',
                'password' => Hash::make('admin123'),
                'username' => '',
                'role' => 'admin',
            ]
        );

        if (!$adminUser1->username) {
            $adminUser1->update([
                'username' => 'ADM' . str_pad($adminUser1->id, 3, '0', STR_PAD_LEFT)
            ]);
        }

        $adminUser2 = User::firstOrCreate(
            ['email' => 'admin2@example.com'],
            [
                'last_name' => 'Dela Cruz',
                'first_name' => 'John',
                'password' => Hash::make('admin123'),
                'username' => '',
                'role' => 'admin',
            ]
        );

        if (!$adminUser2->username) {
            $adminUser2->update([
                'username' => 'ADM' . str_pad($adminUser2->id, 3, '0', STR_PAD_LEFT)
            ]);
        }
        
        // Create teacher if doesn't exist
        $teacher1 = User::firstOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'last_name' => 'Kiskisan',
                'first_name' => 'Nathaniel',
                'middle_name' => 'R',
                'password' => Hash::make('teacher123'),
                'username' => '',
                'role' => 'teacher',
            ]
        );

        if (!$teacher1->username) {
            $teacher1->update([
                'username' => 'TCH' . str_pad($teacher1->id, 5, '0', STR_PAD_LEFT)
            ]);
        }

        Teacher::firstOrCreate(
            ['user_id' => $teacher1->id],
            [
                'employee_id' => $teacher1->username,
                'street_address' => '123 Purok 1',
                'barangay' => 'Barangay San Jose',
                'municipality' => 'Medellin',
                'province' => 'Cebu',
                'phone' => '+639123456745',
                'birthdate' => '1990-01-01',
                'gender' => 'Male',
                'date_joined' => '2020-01-01',
                'status' => 'active',
            ]
        );

        // Create sections if they don't exist
        $sectionA = Section::firstOrCreate(
            ['name' => 'Section A'],
            [
                'grade_level' => 'Grade 10',
                'description' => 'Section A for Grade 10',
                'status' => 'active',
                'adviser_id' => $teacher1->id
            ]
        );

        $sectionB = Section::firstOrCreate(
            ['name' => 'Section B'],
            [
                'grade_level' => 'Grade 9',
                'description' => 'Section B for Grade 9',
                'status' => 'active',
                'adviser_id' => $teacher1->id
            ]
        );

        // Create student users if they don't exist
        $studentUser1 = User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'last_name' => 'Lumapac',
                'first_name' => 'Leslie',
                'password' => Hash::make('student123'),
                'username' => '',
                'role' => 'student',
            ]
        );

        if (!$studentUser1->username) {
            $studentUser1->update([
                'username' => 'STU' . str_pad($studentUser1->id, 5, '0', STR_PAD_LEFT)
            ]);
        }

        Student::firstOrCreate(
            ['user_id' => $studentUser1->id],
            [
                'student_id' => $studentUser1->username,
                'lrn' => '123456789012',
                'street_address' => '456 Purok 2',
                'barangay' => 'Barangay Poblacion',
                'municipality' => 'Medellin',
                'province' => 'Cebu',
                'phone' => '+639123456789',
                'birthdate' => '2007-06-15',
                'gender' => 'Female',
                'grade_level' => '10',
                'section' => $sectionA->name,
                'status' => 'active',
            ]
        );

        // Create another student if doesn't exist
        $studentUser2 = User::firstOrCreate(
            ['email' => 'student2@example.com'],
            [
                'last_name' => 'Dela Cruz',
                'first_name' => 'Juan',
                'password' => Hash::make('student123'),
                'username' => '',
                'role' => 'student',
            ]
        );

        if (!$studentUser2->username) {
            $studentUser2->update([
                'username' => 'STU' . str_pad($studentUser2->id, 5, '0', STR_PAD_LEFT)
            ]);
        }

        Student::firstOrCreate(
            ['user_id' => $studentUser2->id],
            [
                'student_id' => $studentUser2->username,
                'lrn' => '987654321098',
                'street_address' => '789 Purok 3',
                'barangay' => 'Barangay Luy-a',
                'municipality' => 'Medellin',
                'province' => 'Cebu',
                'phone' => '+639234567890',
                'birthdate' => '2008-03-20',
                'gender' => 'Male',
                'grade_level' => '9',
                'section' => $sectionB->name,
                'status' => 'active',
            ]
        );
    }
}

