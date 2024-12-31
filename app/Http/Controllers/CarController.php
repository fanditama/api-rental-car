<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarCreateRequest;
use App\Http\Resources\CarCollection;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    public function create(CarCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();

        $car = new Car($data);
        $car->user_id = $user->id;
        $car->save();

        return (new CarResource($car))->response()->setStatusCode(201);
    }

    public function get(int $id): CarResource
    {
        $user = Auth::user();
        $car = Car::where('id', $id)->where('user_id', $user->id)->first();

        if (!$car) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return new CarResource($car);
    }

    public function update(int $id, CarCreateRequest $request): CarResource
    {
        $user = Auth::user();
        $car = Car::where('id', $id)->where('user_id', $user->id)->first();

        if (!$car) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        $data = $request->validated();
        $car->fill($data);
        $car->save();

        return new CarResource($car);
    }

    public function delete(int $id): jsonResponse
    {
        $user = Auth::user();
        $car = Car::where('id', $id)->where('user_id', $user->id)->first();

        if (!$car) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        $car->delete();
        return response()->json([
           'data' => true
        ])->setStatusCode(200);
    }

    public function search(Request $request): CarCollection
    {
        $user = Auth::user();
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $cars = Car::query()->where('user_id', $user->id);

        $cars = $cars->where(function (Builder $builder) use ($request) {
            $name = $request->input('name');

            // jika user input name
            if ($name) {
                $builder->where(function (Builder $builder) use ($name) {
                    $builder->orWhere('name', 'like', '%' . $name . '%');
                    $builder->orWhere('brand', 'like', '%' . $name . '%');
                    $builder->orWhere('model', 'like', '%' . $name . '%');
                    $builder->orWhere('year', 'like', '%' . $name . '%');
                    $builder->orWhere('color', 'like', '%' . $name . '%');
                    $builder->orWhere('image', 'like', '%' . $name . '%');
                    $builder->orWhere('transmision', 'like', '%' . $name . '%');
                    $builder->orWhere('seat', 'like', '%' . $name . '%');
                });
            }

            $cost = $request->input('cost');

            // jika user input cost
            if ($cost) {
                $builder->where(function (Builder $builder) use ($cost) {
                    $builder->orWhere('cost_per_day', 'like', '%' . $cost . '%');
                    
                });
            }

            $location = $request->input('location');

            // jika user input location
            if ($location) {
                $builder->where(function (Builder $builder) use ($location) {
                    $builder->orWhere('location', 'like', '%' . $location . '%');
                });
            }

            $available = $request->input('available');

            // jika user input available
            if ($available) {
                $builder->where(function (Builder $builder) use ($available) {
                    $builder->orWhere('available', 'like', '%' . $available . '%');
                });
            }
        });

        $cars = $cars->paginate(perPage: $size, page: $page);

        return new CarCollection($cars);
    }
}
