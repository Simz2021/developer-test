<?php

namespace Tests\Unit\Repositories;

use App\Repositories\UserRepository;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $userRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = new UserRepository();
    }

    public function testFindAll()
    {
        // Create some test users
        factory(User::class, 3)->create();

        $users = $this->userRepository->findAll();

        $this->assertCount(3, $users);
        $this->assertInstanceOf(User::class, $users[0]);
    }

    public function testCreate()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'position' => 'Developer',
        ];

        $user = $this->userRepository->create($data);

        $this->assertDatabaseHas('users', $data);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertEquals($data['position'], $user->position);
    }

    public function testDelete()
    {
        $user = factory(User::class)->create();

        $result = $this->userRepository->delete($user->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
