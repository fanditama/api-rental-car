<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $booking = Booking::where('status', 'PENDING')->first();
        Payment::create([
            'amount' => 10.000,
            'payment_date' => '2014-02-22',
            'status' => 'PENDING',
            'transaction_proof' => 'test',
            'booking_id' => $booking->id,
        ]);
    }
}
