<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@haatbazar.com',
            'phone'    => '01700000000',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);
    }
}
