<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /**
     * Test user update.
     *
     * @return void
     */
    public function testUpdate()
    {
        $this->seed(\UsersTableSeeder::class);

        $user = User::find(1);
        $this->be($user);

        $this->post('api/user', [
            'name' => 'John Doe'
        ])->assertJsonFragment([
            'name' => 'John Doe'
        ]);

        $this->post('api/user', [
            'name' => ''
        ])->assertJsonStructure([
            'errors'
        ]);
    }
}
