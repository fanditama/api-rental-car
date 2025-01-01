<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Car;
use Database\Seeders\BookingSeeder;
use Database\Seeders\CarSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    public function testCreateBookingSuccess(): void
    {
        $this->seed([UserSeeder::class, CarSeeder::class]);
        $car = Car::query()->limit(1)->first();

        $this->post(
            '/api/cars/' . $car->id . '/bookings',
            [
                'start_date' => '2020-12-07',
                'end_date' => '2020-12-12',
                'total_cost' => 5000,
                'status' => 'PENDING',
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(201)
            ->assertJson([
                'data' => [
                    'start_date' => '2020-12-07',
                    'end_date' => '2020-12-12',
                    'total_cost' => 5000,
                    'status' => 'PENDING',
                ]
            ]);
    }

    public function testCreateBookingFailed()
    {
        $this->seed([UserSeeder::class, CarSeeder::class]);
        $car = Car::query()->limit(1)->first();

        $this->post(
            '/api/cars/' . $car->id . '/bookings',
            [
                'start_date' => '2020-12-07',
                'end_date' => '2020-12-12',
                'total_cost' => null,
                'status' => 'PENDING',
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'total_cost' => ['The total cost field is required.']
                ]
            ]);
    }

    public function testCreateBookingNotFound()
    {
        $this->seed([UserSeeder::class, CarSeeder::class]);
        $car = Car::query()->limit(1)->first();

        $this->post(
            '/api/cars/' . ($car->id + 1) . '/bookings',
            [
                'start_date' => '2020-12-07',
                'end_date' => '2020-12-12',
                'total_cost' => 5000,
                'status' => 'PENDING',
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['not found']
                ]
            ]);
    }

    public function testGetBookingSuccess()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class]);
        $booking = Booking::query()->limit(1)->first();

        $this->get('/api/cars/' . $booking->car_id . '/bookings/' . $booking->id, [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'start_date' => '2024-01-01',
                    'end_date' => '2024-01-05',
                    'total_cost' => 500000,
                    'status' => 'PENDING'
                ]
            ]);
    }

    public function testGetCarNotFound()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class]);
        $booking = Booking::query()->limit(1)->first();

        $this->get('/api/cars/' . $booking->car_id . '/bookings/' . ($booking->id + 1), [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['not found']
                ]
            ]);
    }

    public function testUpdateBookingSuccess()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class]);
        $booking = Booking::query()->limit(1)->first();

        $this->put(
            '/api/cars/' . $booking->car_id . '/bookings/' . $booking->id,
            [
                'start_date' => '2024-02-02',
                'end_date' => '2024-02-05',
                'total_cost' => 50000,
                'status' => 'PENDING'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'start_date' => '2024-02-02',
                    'end_date' => '2024-02-05',
                    'total_cost' => 50000,
                    'status' => 'PENDING'
                ]
            ]);
    }

    public function testUpdateBookingFailed()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class]);
        $booking = Booking::query()->limit(1)->first();

        $this->put(
            '/api/cars/' . $booking->car_id . '/bookings/' . $booking->id,
            [
                'start_date' => '2024-02-02',
                'end_date' => '2024-02-05',
                'total_cost' => null,
                'status' => 'PENDING'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'total_cost' => ['The total cost field is required.']
                ]
            ]);
    }

    public function testUpdateBookingNotFound()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class]);
        $booking = Booking::query()->limit(1)->first();

        $this->put(
            '/api/cars/' . $booking->car_id . '/bookings/' . ($booking->id + 1),
            [
                'start_date' => '2024-02-02',
                'end_date' => '2024-02-05',
                'total_cost' => 50000,
                'status' => 'PENDING'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['not found']
                ]
            ]);
    }

    public function testDeleteBookingSuccess()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class]);
        $booking = Booking::query()->limit(1)->first();

        $this->delete(
            '/api/cars/' . $booking->car_id . '/bookings/' . $booking->id,
            [],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);
    }

    public function testDeleteBookingNotFound()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class]);
        $booking = Booking::query()->limit(1)->first();

        $this->delete(
            '/api/cars/' . $booking->car_id . '/bookings/' . ($booking->id + 1),
            [],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['not found']
                ]
            ]);
    }

    public function testListBookingSuccess()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class]);
        $car = Car::query()->limit(1)->first();

        $this->get(
            '/api/cars/' . $car->id . '/bookings',
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'start_date' => '2024-01-01',
                        'end_date' => '2024-01-05',
                        'total_cost' => 500000,
                        'status' => 'PENDING'
                    ]
                ]
            ]);
    }

    public function testListBookingNotFound()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class]);
        $car = Car::query()->limit(1)->first();

        $this->get(
            '/api/cars/' . ($car->id + 1) . '/bookings',
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['not found']
                ]
            ]);
    }
}
