<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;

use Modules\Core\Entities\Staff;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Account
        Staff::create([
            'name' => 'System Administrator',
            'email' => 'admin@nokuex.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Customer Care Staff
        Staff::create([
            'name' => 'Sarah Johnson',
            'email' => 'customercare@nokuex.com',
            'password' => Hash::make('password'),
            'role' => 'customer_care',
            'is_active' => true,
        ]);

        // Finance Staff
        Staff::create([
            'name' => 'Michael Chen',
            'email' => 'finance@nokuex.com',
            'password' => Hash::make('password'),
            'role' => 'finance',
            'is_active' => true,
        ]);

        // Compliance Staff
        Staff::create([
            'name' => 'Emily Rodriguez',
            'email' => 'compliance@nokuex.com',
            'password' => Hash::make('password'),
            'role' => 'compliance',
            'is_active' => true,
        ]);

        // Sales Staff
        Staff::create([
            'name' => 'David Williams',
            'email' => 'sales@nokuex.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
            'is_active' => true,
        ]);

        // Chat Support Staff
        Staff::create([
            'name' => 'Lisa Anderson',
            'email' => 'chat@nokuex.com',
            'password' => Hash::make('password'),
            'role' => 'chat_support',
            'is_active' => true,
        ]);
    }
}
