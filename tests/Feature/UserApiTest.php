<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

use Laravel\Passport\Passport;

use Tests\TestCase;

class UserApiTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /** 
     * @test 
     * */
    public function it_registers_a_new_user()
    {
        // Generate fake data
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        // Make request to register endpoint
        $response = $this->postJson('/api/register', $userData);

        // Assert response status is 200
        $response->assertStatus(200);

        // Assert response structure
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
        ]);

        // Assert response message
        $response->assertJson([
            'status' => true,
            'message' => 'User registered successfully',
        ]);

        // Assert user exists in the database
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);
    }





    /** @test */
    public function it_authenticates_a_user()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'token',
            'data',
        ]);
    }

    /** @test */
    public function it_returns_user_profile()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->getJson('/api/profile');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at',
            ],
        ]);
    }

}
