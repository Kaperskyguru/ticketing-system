<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class AuthTest extends TestCase
{
    /**
     * A basic feature test for Login.
     *
     * @return void
     */
    public function testRequiresEmailAndLogin()
    {
        $res = $this->json('POST', 'api/v1/auth/login')
            ->assertStatus(422)
            ->assertJson(
                [
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'email' => ['The email field is required.'],
                        'password' => ['The password field is required.'],
                    ]

                ]
            );
    }

    public function testUserLoginsSuccessfully()
    {
        $user = factory(User::class)->create([
            'name' => 'Test Test',
            'email' => Str::random(5) . '-test@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        $loginPayload = ['email' => $user->email, 'password' => 'password'];

        $this->json('POST', 'api/v1/auth/login', $loginPayload)
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    "user" => [
                        "id",
                        "name",
                        "email",
                        "email_verified_at",
                        "is_admin",
                        "created_at",
                        "updated_at",
                    ],
                    "access_token",
                    "token_type",
                    "expires_at"

                ]
            );
    }

    public function testRegisterRequiresEmailPasswordAndName()
    {
        $res = $this->json('POST', 'api/v1/auth/register')
            ->assertStatus(422)
            ->assertJson(
                [
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        "name" => ["The name field is required."],
                        'email' => ['The email field is required.'],
                        'password' => ['The password field is required.'],
                    ]

                ]
            );
    }

    public function testRegisterRequiresPasswordConfirmation()
    {
        $payload = [
            'name' => 'Solomon Eseme',
            'email' => 'solomon@test.com',
            'password' => 'password',
            // 'password_confirmation' => 'toptal123',
        ];

        $res = $this->json('POST', 'api/v1/auth/register', $payload)
            ->assertStatus(422)
            ->assertJson(
                [
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'password' => ['The password confirmation does not match.'],
                    ]

                ]
            );
    }

    public function testRegistersSuccessfully()
    {
        $payload = [
            'name' => 'Solomon Eseme',
            'email' => 'solomon@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $res = $this->json('POST', 'api/v1/auth/register', $payload)
            ->assertStatus(201)
            ->assertJsonStructure(
                [
                    "message",
                    "user" => [
                        "name",
                        "email",
                        "updated_at",
                        "created_at",
                        "id"
                    ]
                ]
            );
    }

    public function testEmailAlreadyeenTaken()
    {
        $payload = [
            'name' => 'Solomon Eseme',
            'email' => 'solomon@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $res = $this->json('POST', 'api/v1/auth/register', $payload)
            ->assertStatus(422)
            ->assertJson(
                [
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'email' => ['The email has already been taken.'],
                    ]

                ]
            );

        User::where('email', $payload['email'])->delete();
    }

    public function testUserIsLoggedOutProperly()
    {
        $payload = [
            'name' => 'Solomon Eseme',
            'email' => 'solomon@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
        $user = factory(User::class)->create($payload);

        // Log user in
        $loginPayload = ['email' => $user->email, 'password' => 'password'];
        $res = $this->json('POST', 'api/v1/auth/login', $loginPayload)->assertStatus(200);
        User::where('email', $payload['email'])->delete();
        $this->assertAuthenticated();

        $token = $res->decodeResponseJson()['access_token'];

        $headers = ['Authorization' => "Bearer $token"];

        $this->refreshApplication();
        $this->get('api/v1/events', $headers)->assertStatus(200);
        $this->post('api/v1/auth/logout', [], $headers)->assertStatus(200);
        $this->assertGuest();
    }

    public function testUserWithNullToken()
    {
        $headers = ['Authorization' => "Bearer wrong_token"];
        $this->json('get', 'api/v1/events', [], $headers)->assertStatus(401);
    }
}
