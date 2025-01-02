<?php

namespace Tests\Feature;

use App\Http\Resources\PaymentResource;
use App\Models\Booking;
use App\Models\Payment;
use Database\Seeders\BookingSeeder;
use Database\Seeders\CarSeeder;
use Database\Seeders\PaymentSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    public function testCreatePaymentSuccess()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class]);
        $booking = Booking::query()->limit(1)->first();

        $this->post('/api/bookings/' . $booking->id . '/payments',
            [
                'amount' => 1.000,
                'payment_date' => '2013-09-22',
                'status' => 'PENDING',
                'transaction_proof' => 'test'
            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'amount' => 1.000,
                    'payment_date' => '2013-09-22',
                    'status' => 'PENDING',
                    'transaction_proof' => 'test'
                ]
            ]);
    }

    public function testCreatePaymentFailed()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class]);
        $booking = Booking::query()->limit(1)->first();

        $this->post('/api/bookings/' . $booking->id . '/payments',
            [
                'amount' => 1.000,
                'payment_date' => '',
                'status' => 'PENDING',
                'transaction_proof' => 'test'
            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'payment_date' => [
                        'The payment date field is required.'
                    ]
                ]
            ]);
    }

    public function testCreatePaymentUnauthorized()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class]);
        $booking = Booking::query()->limit(1)->first();

        $this->post('/api/bookings/' . $booking->id . '/payments',
            [
                'amount' => 1.000,
                'payment_date' => '',
                'status' => 'PENDING',
                'transaction_proof' => 'test'
            ],
            [
                'Authorization' => 'salah'
            ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ],
                ]
            ]);
    }

    public function testGetInventorySuccess()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class, PaymentSeeder::class]);
        $payment = Payment::query()->limit(1)->first();

        $this->get('/api/bookings/' . $payment->booking_id . '/payments/' . $payment->id,
            [
                'Authorization' => 'test'
            ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'amount' => 10.000,
                    'payment_date' => '2014-02-22',
                    'status' => 'PENDING',
                    'transaction_proof' => 'test'
                ]
            ]);
    }

    public function testGetInventoryNotFound()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class, PaymentSeeder::class]);
        $payment = Payment::query()->limit(1)->first();

        $this->get('/api/bookings/' . $payment->booking_id . '/payments/' . ($payment->id + 1),
            [
                'Authorization' => 'test'
            ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }

    public function testUpdateInventorySuccess()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class, PaymentSeeder::class]);
        $payment = Payment::query()->limit(1)->first();

        $this->put('/api/bookings/' . $payment->booking_id . '/payments/' . $payment->id, [
                'amount' => 20.000,
                'payment_date' => '2015-02-22',
                'status' => 'PENDING',
                'transaction_proof' => 'test2',
            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'amount' => 20.000,
                    'payment_date' => '2015-02-22',
                    'status' => 'PENDING',
                    'transaction_proof' => 'test2',
                ]
            ]);
    }

    public function testUpdatePaymentValidationError()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class, PaymentSeeder::class]);
        $payment = Payment::query()->limit(1)->first();

        $this->put('/api/bookings/' . $payment->booking_id . '/payments/' . $payment->id, [
                'amount' => 20.000,
                'payment_date' => '2015-02-22',
                'status' => '',
                'transaction_proof' => 'test2',
            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'status' => [
                        'The status field is required.'
                    ]
                ]
            ]);
    }

    public function testDeletePaymentSuccess()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class, PaymentSeeder::class]);
        $payment = Payment::query()->limit(1)->first();

        $this->delete('/api/bookings/' . $payment->booking_id . '/payments/' . $payment->id, [
        ],
        [
            'Authorization' => 'test'
        ])->assertStatus(200)
        ->assertJson([
            'data' => true
        ]);
    }

    public function testDeletePaymentNotFound()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class, PaymentSeeder::class]);
        $payment = Payment::query()->limit(1)->first();

        $this->delete('/api/bookings/' . $payment->booking_id . '/payments/' . ($payment->id + 1), [
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ]);
    }

    public function testListPaymentSuccess()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class, PaymentSeeder::class]);
        $booking = Booking::query()->limit(1)->first();

        $this->get('/api/bookings/' . $booking->id . '/payments',
            [
                'Authorization' => 'test'
            ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'amount' => 10.000,
                        'payment_date' => '2014-02-22',
                        'status' => 'PENDING',
                        'transaction_proof' => 'test',
                    ]
                ]
            ]);
    }

    public function testListPaymentNotFound()
    {
        $this->seed([UserSeeder::class, CarSeeder::class, BookingSeeder::class, PaymentSeeder::class]);
        $booking = Booking::query()->limit(1)->first();

        $this->get('/api/bookings/' . $booking->id . '/payments/1',
            [
                'Authorization' => 'test'
            ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ]);
    }
}
