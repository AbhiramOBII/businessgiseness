<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Update the admin user to have admin privileges
        $adminUser = User::where('email', 'admin@businessgiseness.com')->first();
        
        if ($adminUser) {
            $adminUser->update(['is_admin' => true]);
            $this->command->info('Admin privileges granted to: ' . $adminUser->email);
        } else {
            $this->command->error('Admin user not found with email: admin@businessgiseness.com');
        }
    }
}
