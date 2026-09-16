<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admintomeetcafe@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('PasswordAdmin123!'), // Password default yang aman
                'email_verified_at' => now(),
            ]
        );
    }
}