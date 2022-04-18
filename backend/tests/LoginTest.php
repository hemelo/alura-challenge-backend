<?php

namespace Tests;

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
    * Test login and get token back.
    *
    * @return void
    */
    public function testLogin()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Cors::class);

        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '123456'
        ]);
        
        $response->seeStatusCode(200);
        
        $res_array = (array) json_decode($this->response->content());
        $this->assertArrayHasKey('token', $res_array);
    }

    /**
    * Register an API user
    *
    * @return void
    */
    public function testRegistration()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Cors::class);

        $response = $this->post('/api/register', [
            'name' => 'Henrique Melo',
            'email' => 'hmelo2509@gmail.com',
            'password' => '12345678'
        ]);
        
        $response->seeStatusCode(201);
        $response->seeJsonContains(['message' => 'User has been created with success.']);
    }

    /**
    * Test invalid registration of an API user
    *
    * @return void
    */
    public function testInvalidRegistration()
    {
        $this->withoutMiddleware(\App\Http\Middleware\Cors::class);
        
        $response = $this->post('/api/register', [
            'email' => 'hmelo2509gmail.com',
            'password' => '12345678'
        ]);
        
        $response->seeStatusCode(422);
    }

    /**
    * Test JWT token
    *
    * @return void
    */
    public function testIfBearerTokenWorks()
    {
        $this->call('POST', '/api/register', [
            'name' => 'Henrique Melo',
            'email' => 'hmelo2509@gmail.com',
            'password' => '12345678'
        ]);
        
        $response = $this->call('POST', '/api/login', [
            'email' => 'hmelo2509@gmail.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);


        $token = $response['token'];

        $response = $this->json('GET', '/api/transferencias', [], ['HTTP_Authorization' => 'Authorization', 'Bearer: ' . $token]);

        $response->assertNotEquals(401, $response->getStatus());
    }
}
