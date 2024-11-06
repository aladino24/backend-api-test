<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'age' => 30,
                'membership_status' => 'active',
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Editor User',
                'email' => 'editor@example.com',
                'age' => 25,
                'membership_status' => 'active',
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'age' => 22,
                'membership_status' => 'active',
                'password' => Hash::make('password123')
            ],
        ]);
    }
}
