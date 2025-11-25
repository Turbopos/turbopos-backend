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
        User::create([
            'username' => "admin",
            'password' => 'admin',
            'nama' => "Admin",
            'role' => User::ADMIN,
            'email_verified_at' => now(),
        ]);
    }
}
