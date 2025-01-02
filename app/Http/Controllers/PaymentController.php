<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentCreateRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\PaymentResource;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(int $idBooking, PaymentCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $booking = Booking::where('id', $idBooking)->first();

        $payment = new Payment($data);
        $payment->booking_id = $booking->id;
        $payment->save();

        return (new PaymentResource($payment))->response()->setStatusCode(201);
    }

    public function get(int $idBooking, int $idPayment): PaymentResource
    {
        $payment = Payment::where('booking_id', $idBooking)->where('id', $idPayment)->first();

        if (!$payment) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return new paymentResource($payment);
    }

    public function update(int $idBooking, int $idPayment, PaymentCreateRequest $request): PaymentResource
    {
        $payment = Payment::where('booking_id', $idBooking)->where('id', $idPayment)->first();

        if (!$payment) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        $data = $request->validated();
        $payment->fill($data);
        $payment->save();

        return new PaymentResource($payment);
    }

    public function delete(int $idBooking, int $idPayment): jsonResponse
    {
        $payment = Payment::where('booking_id', $idBooking)->where('id', $idPayment)->first();

        if (!$payment) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        $payment->delete();
        return response()->json([
           'data' => true
        ])->setStatusCode(200);
    }

    public function list(int $idBooking): JsonResponse
    {
        $payment = Payment::where('booking_id', $idBooking)->get();

        if (!$payment) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return (PaymentResource::collection($payment))->response()->setStatusCode(200);
    }
}
