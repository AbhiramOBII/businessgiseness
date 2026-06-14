<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update admin user
        $adminUser = User::where('email', 'admin@businessgiseness.com')->first();
        
        if (!$adminUser) {
            User::create([
                'name' => 'Abhiram Chandramohan',
                'email' => 'admin@businessgiseness.com',
                'email_verified_at' => now(),
                'password' => Hash::make('9964331200#@!'),
                'is_admin' => true,
            ]);
            
            $this->command->info('Admin user created successfully!');
        } else {
            // Update existing admin user password
            $adminUser->update([
                'password' => Hash::make('9964331200#@!'),
                'is_admin' => true,
            ]);
            
            $this->command->info('Admin user password updated successfully!');
        }
        
        $this->command->info('Email: admin@businessgiseness.com');
        $this->command->info('Password: 9964331200#@!');
    }
}
