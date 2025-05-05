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
        // Example student user
        $adminUser1 = User::create([
            'name' => 'Mae Matanog',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'username' => '',
            'role' => 'admin',
        ]);

        $adminUser1->update([
            'username' => 'ADM' . str_pad($adminUser1->id, 3, '0', STR_PAD_LEFT)
        ]);

        $adminUser2 = User::create([
            'name' => 'Mae Matanog',
            'email' => 'admin2@example.com',
            'password' => Hash::make('admin2'),
            'username' => '',
            'role' => 'admin',
        ]);

        $adminUser2->update([
            'username' => 'ADM' . str_pad($adminUser2->id, 3, '0', STR_PAD_LEFT)
        ]);
        
        // Add more if you want
       $teacher1 = User::create([
            'name' => 'Nathaniel R. Kiskisan',
            'email' => 'teacher@example.com',
            'password' => Hash::make('teacher'),
            'username' => '',
            'role' => 'teacher',
        ]);

        $teacher1->update([
            'username' => 'TCH' . str_pad($teacher1->id, 5, '0', STR_PAD_LEFT)
        ]);

        Teacher::create([
            'user_id' => $teacher1->id,
            'employee_id' => $teacher1->username,
            'department' => 'Math',
            'position' => 'Teacher',
            'address' => 'Leyte',
            'phone' => '09123456745',
            'birthdate' => '01/01/2003',
            'gender' => 'male',
            'date_joined' => '01/01/2025',
            'qualification' => 'masunurin',
            'specialization' => 'Math',
            'status' => 'active',
        ]);

        $studentUser1 = User::create([
            'name' => 'Leslie Lumapac',
            'email' => 'student@example.com',
            'password' => Hash::make('student'),
            'username' => '',
            'role' => 'student',
        ]);

        $studentUser1->update([
            'username' => 'STU' . str_pad($studentUser1->id, 5, '0', STR_PAD_LEFT)
        ]);

        Student::create([
        'user_id' => $studentUser1->id,
        'student_id' => $studentUser1->username,
        'phone' => '09123456745',
        'address' => 'Leyte',
        'birthdate' => '01/01/2003',
        'gender' => 'male',
        'guardian_name' => 'juan',
        'guardian_phone' => '12345678901',
        'guardian_email' => 'juan@gmail.com',
        'status' => 'active',
        ]);
    }
}

