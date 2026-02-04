<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin account (no school association)
        $superAdmin = User::firstOrCreate(
            ['nip' => 'admin'],
            [
                'name' => 'Super Admin',
                'nip' => 'admin',
                'email' => 'admin@sapabk.local',
                'password' => Hash::make('admin123'),
                'must_change_password' => true,
                'role' => User::ROLE_SUPER_ADMIN,
                'school_id' => null,
            ]
        );

        $this->command->info('=== SUPER ADMIN CREDENTIALS ===');
        $this->command->info('NIP:      admin');
        $this->command->info('Password: admin123');
        $this->command->info('(Must change password on first login)');
        $this->command->newLine();

        // Run other seeders
        $this->call([
            TestDataSeeder::class,
            WelcomeQuizSeeder::class,
        ]);
    }
}
