<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin users
        $adminUser1 = User::create([
            'last_name' => 'Matanog',
            'first_name' => 'Mae',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'username' => '',
            'role' => 'admin',
        ]);

        $adminUser1->update([
            'username' => 'ADM' . str_pad($adminUser1->id, 3, '0', STR_PAD_LEFT)
        ]);

        $adminUser2 = User::create([
            'last_name' => 'Dela Cruz',
            'first_name' => 'John',
            'email' => 'admin2@example.com',
            'password' => Hash::make('admin123'),
            'username' => '',
            'role' => 'admin',
        ]);

        $adminUser2->update([
            'username' => 'ADM' . str_pad($adminUser2->id, 3, '0', STR_PAD_LEFT)
        ]);
        
        // Create teacher
        $teacher1 = User::create([
            'last_name' => 'Kiskisan',
            'first_name' => 'Nathaniel',
            'middle_name' => 'R',
            'email' => 'teacher@example.com',
            'password' => Hash::make('teacher123'),
            'username' => '',
            'role' => 'teacher',
        ]);

        $teacher1->update([
            'username' => 'TCH' . str_pad($teacher1->id, 5, '0', STR_PAD_LEFT)
        ]);

        Teacher::create([
            'user_id' => $teacher1->id,
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
        ]);

        // Create student users
        $studentUser1 = User::create([
            'last_name' => 'Lumapac',
            'first_name' => 'Leslie',
            'email' => 'student@example.com',
            'password' => Hash::make('student123'),
            'username' => '',
            'role' => 'student',
        ]);

        $studentUser1->update([
            'username' => 'STU' . str_pad($studentUser1->id, 5, '0', STR_PAD_LEFT)
        ]);

        Student::create([
            'user_id' => $studentUser1->id,
            'student_id' => $studentUser1->username,
            'street_address' => '456 Purok 2',
            'barangay' => 'Barangay Poblacion',
            'municipality' => 'Medellin',
            'province' => 'Cebu',
            'phone' => '+639123456789',
            'birthdate' => '2007-06-15',
            'gender' => 'Female',
            'grade_level' => '10',
            'section' => 'A',
            'status' => 'active',
        ]);

        // Create another student for variety
        $studentUser2 = User::create([
            'last_name' => 'Dela Cruz',
            'first_name' => 'Juan',
            'email' => 'student2@example.com',
            'password' => Hash::make('student123'),
            'username' => '',
            'role' => 'student',
        ]);

        $studentUser2->update([
            'username' => 'STU' . str_pad($studentUser2->id, 5, '0', STR_PAD_LEFT)
        ]);

        Student::create([
            'user_id' => $studentUser2->id,
            'student_id' => $studentUser2->username,
            'street_address' => '789 Purok 3',
            'barangay' => 'Barangay Luy-a',
            'municipality' => 'Medellin',
            'province' => 'Cebu',
            'phone' => '+639234567890',
            'birthdate' => '2008-03-20',
            'gender' => 'Male',
            'grade_level' => '9',
            'section' => 'B',
            'status' => 'active',
        ]);
    }
}

