<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $car = Car::query()->limit(1)->first();
        Booking::create([
            'car_id' => $car->id,
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-05',
            'total_cost' => 500000,
            'status' => 'PENDING'
        ]);
    }
}
