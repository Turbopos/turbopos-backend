<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => "admin@demo.com",
            'password' => 'admin',
            'nama' => "Admin",
            'role' => User::ADMIN,
            'email_verified_at' => now(),
        ]);
    }
}
