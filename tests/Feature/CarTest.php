<?php

namespace Tests\Feature;

use App\Models\Car;
use Database\Seeders\CarSearchSeeder;
use Database\Seeders\CarSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CarTest extends TestCase
{
    public function testCreateCarSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/cars',
            [
                'name' => 'kijang',
                'brand' => 'toyota',
                'model' => 'minibus',
                'year' => 2005,
                'color' => 'dark',
                'image' => 'image.png',
                'transmision' => 'AUTOMATIC',
                'seat' => 4,
                'cost_per_day' => 1.000,
                'location' => 'pontianak',
                'available' => 'YES',
            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'kijang',
                    'brand' => 'toyota',
                    'model' => 'minibus',
                    'year' => 2005,
                    'color' => 'dark',
                    'image' => 'image.png',
                    'transmision' => 'AUTOMATIC',
                    'seat' => 4,
                    'cost_per_day' => 1.000,
                    'location' => 'pontianak',
                    'available' => 'YES',
                ]
            ]);
    }

    public function testCreateCarFailed()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/cars',
            [
                'name' => '',
                'brand' => '',
                'model' => '',
                'year' => null,
                'color' => '',
                'image' => '',
                'transmision' => '',
                'seat' => null,
                'cost_per_day' => null,
                'location' => 'pontianak',
                'available' => '',
            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ],
                    'brand' => [
                        'The brand field is required.'
                    ],
                    'model' => [
                        'The model field is required.'
                    ],
                    'year' => [
                        'The year field is required.'
                    ],
                    'color' => [
                        'The color field is required.'
                    ],
                    'image' => [
                        'The image field is required.'
                    ],
                    'transmision' => [
                        'The transmision field is required.'
                    ],
                    'seat' => [
                        'The seat field is required.'
                    ],
                    'cost_per_day' => [
                        'The cost per day field is required.'
                    ],
                    'available' => [
                        'The available field is required.'
                    ],
                ]
            ]);
    }

    public function testCreateAuthorUnauthorized()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/cars',
            [
                'name' => '',
                'brand' => '',
                'model' => '',
                'year' => null,
                'color' => '',
                'image' => '',
                'transmision' => '',
                'seat' => null,
                'cost_per_day' => null,
                'location' => 'pontianak',
                'available' => '',
            ],
            [
                'Authorization' => 'salah'
            ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);
    }

    public function testGetCarSuccess()
    {
        $this->seed([UserSeeder::class, CarSeeder::class]);
        $car = Car::query()->limit(1)->first();

        $this->get('/api/cars/' . $car->id,[
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
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
                ]
            ]);
    }

    public function testGetCarNotFound()
    {
        $this->seed([UserSeeder::class, CarSeeder::class]);
        $car = Car::query()->limit(1)->first();

        $this->get('/api/cars/' . ($car->id + 1),[
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

    public function testUpdateCarSuccess()
    {
        $this->seed([UserSeeder::class, CarSeeder::class]);
        $car = Car::query()->limit(1)->first();

        $this->put('/api/cars/' . $car->id, [
            'name' => 'test2',
            'brand' => 'test2',
            'model' => 'test2',
            'year' => 2004,
            'color' => 'test2',
            'image' => 'test2.png',
            'transmision' => 'MANUAL',
            'seat' => 5,
            'cost_per_day' => 10.000,
            'location' => 'test2',
            'available' => 'NO',
        ],[
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'test2',
                    'brand' => 'test2',
                    'model' => 'test2',
                    'year' => 2004,
                    'color' => 'test2',
                    'image' => 'test2.png',
                    'transmision' => 'MANUAL',
                    'seat' => 5,
                    'cost_per_day' => 10.000,
                    'location' => 'test2',
                    'available' => 'NO',
                ]
            ]);
    }

    public function testUpdateCarValidationError()
    {
        $this->seed([UserSeeder::class, CarSeeder::class]);
        $car = Car::query()->limit(1)->first();

        $this->put('/api/cars/' . $car->id, [
            'name' => '',
            'brand' => '',
            'model' => '',
            'year' => null,
            'color' => '',
            'image' => '',
            'transmision' => '',
            'seat' => null,
            'cost_per_day' => null,
            'location' => 'pontianak',
            'available' => '',
        ],[
            'Authorization' => 'test'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ],
                    'brand' => [
                        'The brand field is required.'
                    ],
                    'model' => [
                        'The model field is required.'
                    ],
                    'year' => [
                        'The year field is required.'
                    ],
                    'color' => [
                        'The color field is required.'
                    ],
                    'image' => [
                        'The image field is required.'
                    ],
                    'transmision' => [
                        'The transmision field is required.'
                    ],
                    'seat' => [
                        'The seat field is required.'
                    ],
                    'cost_per_day' => [
                        'The cost per day field is required.'
                    ],
                    'available' => [
                        'The available field is required.'
                    ],
                ]
            ]);
    }

    public function testDeleteCarSucces() {
        $this->seed([UserSeeder::class, CarSeeder::class]);
        $car = Car::query()->limit(1)->first();

        $this->delete('/api/cars/' . $car->id, [], [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);
    }

    public function testDeleteCarNotFound() 
    {
        $this->seed([UserSeeder::class, CarSeeder::class]);
        $car = Car::query()->limit(1)->first();

        $this->delete('/api/cars/' . ($car->id + 1), [], [
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

    public function testSearchCarByName()
    {
        $this->seed([UserSeeder::class, CarSearchSeeder::class]);

        $response = $this->get('/api/cars?name=car_name', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    
    }

    public function testSearchCarByBrand()
    {
        $this->seed([UserSeeder::class, CarSearchSeeder::class]);

        $response = $this->get('/api/cars?name=car_brand', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    
    }
    public function testSearchCarByModel()
    {
        $this->seed([UserSeeder::class, CarSearchSeeder::class]);

        $response = $this->get('/api/cars?name=car_model', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSearchCarByYear()
    {
        $this->seed([UserSeeder::class, CarSearchSeeder::class]);

        $response = $this->get('/api/cars?name=2000', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSearchCarByColor()
    {
        $this->seed([UserSeeder::class, CarSearchSeeder::class]);

        $response = $this->get('/api/cars?name=car_color', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSearchCarByImage()
    {
        $this->seed([UserSeeder::class, CarSearchSeeder::class]);

        $response = $this->get('/api/cars?name=car_image', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSearchCarByTransmision()
    {
        $this->seed([UserSeeder::class, CarSearchSeeder::class]);

        $response = $this->get('/api/cars?name=AUTOMATIC', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSearchCarBySeat()
    {
        $this->seed([UserSeeder::class, CarSearchSeeder::class]);

        $response = $this->get('/api/cars?name=1', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSearchCarByCostPerDay()
    {
        $this->seed([UserSeeder::class, CarSearchSeeder::class]);

        $response = $this->get('/api/cars?cost=1', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSearchCarByLocation()
    {
        $this->seed([UserSeeder::class, CarSearchSeeder::class]);

        $response = $this->get('/api/cars?location=car_location', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSearchCarByAvailable()
    {
        $this->seed([UserSeeder::class, CarSearchSeeder::class]);

        $response = $this->get('/api/cars?available=YES', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSearchCarNotFound()
    {
        $this->seed([UserSeeder::class, CarSearchSeeder::class]);

        $response = $this->get('/api/cars?name=not_found', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(0, count($response['data']));
        self::assertEquals(0, $response['meta']['total']);
    }

    public function testSearchCarWithPage()
    {
        $this->seed([UserSeeder::class, CarSearchSeeder::class]);

        $response = $this->get('/api/cars?size=5&page=2', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(5, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
        self::assertEquals(2, $response['meta']['current_page']);
    }
}
