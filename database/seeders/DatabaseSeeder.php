<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user and default site settings
        $this->call([
            AdminUserSeeder::class,
            SiteSettingsSeeder::class,
        ]);

        // Uncomment below to create test users
        // User::factory(10)->create();
    }
}
