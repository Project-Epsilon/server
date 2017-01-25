<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * An login test example.
     *
     * @return void
     */
    public function testLogin()
    {
        factory(\App\User::class)->create([
            'email' => 'username@email.com',
            'password' => bcrypt('password')
        ]);

        $this->json('POST', 'api/login', [
            'email' => 'username@email.com',
            'password' => 'password'
        ])->see('token');
    }
}
