<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'test',
            'password' => Hash::make('test'),
            'name' => 'test',
            'email' => 'test@email.com',
            'phone' => '+62',
            'role' => 'ADMIN',
            'token' => 'test'
        ]);
    }
}
