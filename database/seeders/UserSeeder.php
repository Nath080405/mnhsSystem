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
                'middle_name' => 'Dela Cruz',
                'suffix' => null,
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

        // Create a second admin user
        $adminUser2 = User::firstOrCreate(
            ['email' => 'admin2@example.com'],
            [
                'last_name' => 'Smith',
                'first_name' => 'John',
                'middle_name' => 'Robert',
                'suffix' => null,
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
    }
}

