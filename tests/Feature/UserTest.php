<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegisterUserSuccess()
    {
        $this->post('/api/users', [
            'username' => 'pratama',
            'password' => 'rahasia',
            'name' => 'Affandi Pratama',
            'email' => 'fandi@email.com',
            'phone' => '+62',
            'role' => 'ADMIN',
        ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'username' => 'pratama',
                    'name' => 'Affandi Pratama',
                    'email' => 'fandi@email.com',
                    'phone' => '+62',
                    'role' => 'ADMIN',
                ]
            ]);
    }

    public function testRegisterUserFailed()
    {
        $this->post('/api/users', [
            'username' => '',
            'password' => '',
            'name' => '',
            'email' => '',
            'phone' => '',
            'role' => '',
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'username' => [
                        'The username field is required.'
                    ],
                    "password" => [
                        "The password field is required."
                    ],
                    "name" => [
                        "The name field is required."
                    ],
                    "email" => [
                        "The email field is required."
                    ],
                    "phone" => [
                        "The phone field is required."
                    ],
                    "role" => [
                        "The role field is required."
                    ]
                ]
            ]);
    }

    public function testRegisterUserUsernameAlreadyExists()
    {
        $this->testRegisterUserSuccess();

        $this->post('/api/users', [
            'username' => 'pratama',
            'password' => 'rahasia',
            'name' => 'Affandi Pratama',
            'email' => 'fandi@email.com',
            'phone' => '+62',
            'role' => 'ADMIN',
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'username' => [
                        'username already registered'
                    ]
                ]
            ]);
    }

    public function testLoginUserSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'test',
                    'email' => 'test@email.com',
                    'phone' => '+62',
                    'role' => 'ADMIN',
                ]
            ]);

        $user = User::where('username', 'test')->first();
        self::assertNotNull($user->token);
    }

    public function testLoginFailedUsernameUserNotFound()
    {
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'username or password wrong'
                    ]
                ]
            ]);
    }

    public function testLoginFailedPasswordUsersWrong()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'username or password wrong'
                    ]
                ]
            ]);
    }

    public function testGetUserSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current', [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'test',
                    'email' => 'test@email.com',
                    'phone' => '+62',
                    'role' => 'ADMIN',
                ]
            ]);
    }

    public function testGetUserUnauthorized()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current', [])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);
    }

    public function testGetUserInvalidToken()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current', [
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

    public function testUpdatePasswordUserSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('username', 'test')->first();

        $this->patch('/api/users/current', [
            'password' => 'baru'
        ],[
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'test',
                    'email' => 'test@email.com',
                    'phone' => '+62',
                    'role' => 'ADMIN',
                ]
            ]);

        $newUser = User::where('username', 'test')->first();
        self::assertNotEquals($oldUser->password, $newUser->password);
    }

    public function testUpdateNameSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('username', 'test')->first();

        $this->patch('/api/users/current',
            [
                'name' => 'fandi'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'fandi',
                    'email' => 'test@email.com',
                    'phone' => '+62',
                    'role' => 'ADMIN',
                ]
            ]);

        $newUser = User::where('username', 'test')->first();
        self::assertNotEquals($oldUser->name, $newUser->name);
    }

    public function testUpdateEmailSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('username', 'test')->first();

        $this->patch('/api/users/current',
            [
                'email' => 'fandi@example.com'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'test',
                    'email' => 'fandi@example.com',
                    'phone' => '+62',
                    'role' => 'ADMIN',
                ]
            ]);

        $newUser = User::where('username', 'test')->first();
        self::assertNotEquals($oldUser->email, $newUser->email);
    }

    public function testUpdatePhoneSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('username', 'test')->first();

        $this->patch('/api/users/current',
            [
                'phone' => '+12'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'test',
                    'email' => 'test@email.com',
                    'phone' => '+12',
                    'role' => 'ADMIN',
                ]
            ]);

        $newUser = User::where('username', 'test')->first();
        self::assertNotEquals($oldUser->phone, $newUser->phone);
    }

    public function testUpdateRoleSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('username', 'test')->first();

        $this->patch('/api/users/current',
            [
                'role' => 'USER'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test',
                    'name' => 'test',
                    'email' => 'test@email.com',
                    'phone' => '+62',
                    'role' => 'USER',
                ]
            ]);

        $newUser = User::where('username', 'test')->first();
        self::assertNotEquals($oldUser->role, $newUser->role);
    }

    public function testUpdateFailed()
    {
        $this->seed([UserSeeder::class]);

        $this->patch('/api/users/current',
            [
                'name' => 'fandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandifandi'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' =>
                        [
                            'The name field must not be greater than 100 characters.'
                        ]
                ]
            ]);
    }

    public function testLogoutSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->delete(uri: '/api/users/logout', headers: [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);

        $user = User::where('username', 'test')->first();
        self::assertNull($user->token);
    }

    public function testLogoutFailed()
    {
        $this->seed([UserSeeder::class]);

        $this->delete(uri: '/api/users/logout', headers: [
            'Authorization' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "message" => [
                        'unauthorized'
                    ]
                ]
            ]);
    }
}
