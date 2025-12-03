<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@rjsevents.com'],
            [
                'name' => 'Admin User',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'middle_initial' => null,
                'password' => 'admin123',
                'role' => 'admin',
            ]
        );

        // Update password and role if user already exists (to fix any password issues)
        if ($admin->wasRecentlyCreated === false) {
            $admin->update([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'middle_initial' => null,
                'password' => 'admin123', // The User model's 'hashed' cast will automatically hash this
                'role' => 'admin',
            ]);
        }

        $this->command->info('Admin user created/updated successfully!');
        $this->command->info('Email: admin@rjsevents.com');
        $this->command->info('Password: admin123');
    }
}
