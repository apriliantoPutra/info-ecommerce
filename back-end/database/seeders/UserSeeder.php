<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert(
            [
                'username' => 'admin1',
                'email'    => 'admin1@gmail.com',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
