<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::firstOrCreate(
            ['email' => 'user1@example.com'],
            [
                'name' => 'Normal User 1',
                'password' => Hash::make('password_user1'),
                'role' => 'user',
            ]
        );
    }
}
