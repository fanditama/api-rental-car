<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/users', [UserController::class, 'register']);
Route::post('/users/login', [UserController::class, 'login']);

Route::middleware(ApiAuthMiddleware::class)->group(function () {
    Route::get('/users/current', [UserController::class, 'get']);
    Route::patch('/users/current', [UserController::class, 'update']);
    Route::delete('/users/logout', [UserController::class, 'logout']);

    Route::post('/cars', [CarController::class, 'create']);
    Route::get('/cars', [CarController::class, 'search']);
    Route::get('/cars/{id}', [CarController::class, 'get'])->where('id', '[0-9]+');
    Route::put('/cars/{id}', [CarController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/cars/{id}', [CarController::class, 'delete'])->where('id', '[0-9]+');

    Route::post('/cars/{idCar}/bookings', [BookingController::class, 'create'])
        ->where('idCar', '[0-9]+');
    Route::get('/cars/{idCar}/bookings', [BookingController::class, 'list'])
        ->where('idCar', '[0-9]+');
    Route::get('/cars/{idCar}/bookings/{idBooking}', [BookingController::class, 'get'])
        ->where('idCar', '[0-9]+')
        ->where('idBooking', '[0-9]+');
    Route::put('/cars/{idCar}/bookings/{idBooking}', [BookingController::class, 'update'])
        ->where('idCar', '[0-9]+')
        ->where('idBooking', '[0-9]+');
    Route::delete('/cars/{idCar}/bookings/{idBooking}', [BookingController::class, 'delete'])
        ->where('idCar', '[0-9]+')
        ->where('idBooking', '[0-9]+');
    
    Route::post('bookings/{idBooking}/payments', [PaymentController::class, 'create'])->where('idBooking', '[0-9]+');
    Route::get('bookings/{idBooking}/payments', [PaymentController::class, 'list'])->where('idBooking', '[0-9]+');
    Route::get('bookings/{idBooking}/payments/{idPayment}', [PaymentController::class, 'get'])->where('idBooking', '[0-9]+')->where('idPayment', '[0-9]+');
    Route::put('bookings/{idBooking}/payments/{idPayment}', [PaymentController::class, 'update'])->where('idBooking', '[0-9]+')->where('idPayment', '[0-9]+');
    Route::delete('bookings/{idBooking}/payments/{idPayment}', [PaymentController::class, 'delete'])->where('idBooking', '[0-9]+')->where('idPayment', '[0-9]+');
});
