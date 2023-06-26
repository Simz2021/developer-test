<?php

namespace Tests\Feature\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $userRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->app->make(UserRepositoryInterface::class);
    }

    public function testIndex()
    {
        factory(User::class, 5)->create();

        $response = $this->get('/users');

        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    public function testStore()
    {
        $userData = [
            'name' => $this->faker->name,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->email,
            'position' => $this->faker->jobTitle,
        ];

        $response = $this->post('/users', $userData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'message' => 'User added successfully']);

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'] . ' ' . $userData['surname'],
            'email' => $userData['email'],
            'position' => $userData['position'],
        ]);
    }

    public function testDestroy()
    {
        $user = factory(User::class)->create();

        $response = $this->delete('/users/' . $user->id);

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'message' => 'User deleted successfully']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
