<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarSearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'test')->first();

        for ($i=1; $i < 21; $i++) { 
            Car::create([
                'name' => 'car_name' . $i,
                'brand' => 'car_brand' . $i,
                'model' => 'car_model',
                'year' => 2000 . $i,
                'color' => 'car_color' . $i,
                'image' => 'car_image' . $i,
                'transmision' => 'AUTOMATIC',
                'seat' => 1 . $i,
                'cost_per_day' => 1 . $i,
                'location' => 'car_location' . $i,
                'available' => 'YES',
                'user_id' => $user->id
            ]);
        }
    }
}
