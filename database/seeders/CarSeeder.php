<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'test')->first();
        Car::create([
            'name' => 'test',
            'brand' => 'test',
            'model' => 'test',
            'year' => 2005,
            'color' => 'test',
            'image' => 'test.png',
            'transmision' => 'AUTOMATIC',
            'seat' => 4,
            'cost_per_day' => 1.000,
            'location' => 'test',
            'available' => 'YES',
            'user_id' => $user->id
        ]);
    }
}
