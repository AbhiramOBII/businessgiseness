<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates the default admin if it doesn't exist. The password comes from
     * ADMIN_PASSWORD in .env, or a random one is generated and printed once.
     * An existing admin's password is never changed.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@businessgiseness.com');
        $adminUser = User::where('email', $email)->first();

        if ($adminUser) {
            $adminUser->forceFill(['is_admin' => true])->save();
            $this->command->info("Admin user {$email} already exists; admin privileges ensured.");
            return;
        }

        $password = env('ADMIN_PASSWORD') ?: Str::password(16);

        User::forceCreate([
            'name' => 'Abhiram Chandramohan',
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info("Email: {$email}");
        if (!env('ADMIN_PASSWORD')) {
            $this->command->warn("Generated password (shown once): {$password}");
        }
    }
}
