<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        
        // Core Module Seeders
        $this->call([
            \Modules\Core\Database\Seeders\StaffSeeder::class,
        ]);

        // Sales Module Seeders
        if (class_exists(\Modules\Sales\Database\Seeders\LeadsSeeder::class)) {
            $this->call(\Modules\Sales\Database\Seeders\LeadsSeeder::class);
        }

        // Compliance Module Seeders
        if (class_exists(\Modules\Compliance\Database\Seeders\ComplianceSeeder::class)) {
            $this->call(\Modules\Compliance\Database\Seeders\ComplianceSeeder::class);
        }

        $this->command->info('✅ Database seeding completed successfully!');
    }
}
