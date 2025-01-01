<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingCreateRequest;
use App\Http\Requests\BookingUpdateRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    private function getCar(User $user, int $idCar): Car
    {
        $car = Car::where('user_id', $user->id)->where('id', $idCar)->first();
        if (!$car) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $car;
    }

    private function getBooking(Car $car, int $idBooking): Booking
    {
        $booking = Booking::where('car_id', $car->id)->where('id', $idBooking)->first();
        if (!$booking) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $booking;
    }

    public function create(int $idCar, BookingCreateRequest $request): JsonResponse
    {
        $user = Auth::user();
        $car = $this->getCar($user, $idCar);

        $data = $request->validated();
        $booking = new Booking($data);
        $booking->car_id = $car->id;
        $booking->save();

        return (new BookingResource($booking))->response()->setStatusCode(201);
    }

    public function get(int $idCar, int $idBooking): BookingResource
    {
        $user = Auth::user();
        $car = $this->getCar($user, $idCar);
        $booking = $this->getBooking($car, $idBooking);

        return new BookingResource($booking);
    }

    public function update(int $idCar, int $idBooking, BookingUpdateRequest $request): BookingResource
    {
        $user = Auth::user();
        $car = $this->getCar($user, $idCar);
        $booking = $this->getBooking($car, $idBooking);

        $data = $request->validated();
        $booking->fill($data);
        $booking->save();

        return new BookingResource($booking);
    }

    public function delete(int $idCar, int $idBooking): JsonResponse
    {
        $user = Auth::user();
        $car = $this->getCar($user, $idCar);
        $booking = $this->getBooking($car, $idBooking);
        $booking->delete();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }

    public function list(int $idCar): JsonResponse
    {
        $user = Auth::user();
        $car = $this->getCar($user, $idCar);
        $bookings = Booking::where('car_id', $car->id)->get();
        return (BookingResource::collection($bookings))->response()->setStatusCode(200);
    }
}
