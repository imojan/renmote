<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed admin account.
     * Hanya 1 admin — langsung di-set, tidak bisa register dari form.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@renmote.com'],
            [
                'name'              => 'Admin Renmote',
                'password'          => Hash::make('admin1234'),
                'role'              => 'admin',
                'email_verified_at' => now(),
                'is_phone_verified' => true,
            ]
        );

        $this->command->info('Admin account seeded: admin@renmote.com / admin1234');
    }
}
