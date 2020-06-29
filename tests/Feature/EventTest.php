<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class EventTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRetrievesAllEvents()
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

        $token = $res->decodeResponseJson()['access_token'];

        $headers = ['Authorization' => "Bearer $token"];

        $this->refreshApplication();
        $this->get('api/v1/events', $headers)->assertSuccessful()
            ->assertStatus(200);
    }


    public function testRetrievesAllEventsWithoutToken()
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
        $this->post('api/v1/auth/logout', [], $headers)->assertStatus(200);
        $this->assertGuest();
        $this->get('api/v1/events')->assertUnauthorized()->assertStatus(401);
    }
}
