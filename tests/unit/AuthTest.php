<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Logs in and attempts access JWT protected routes.
     */
    public function testLogin()
    {
        factory(\App\User::class)->create([
            'email' => 'username@email.com',
            'password' => bcrypt('password')
        ]);

        $res = $this->call('POST', 'api/login', [
            'email' => 'username@email.com',
            'password' => 'password'
        ]);

        //Retrieve token.
        $data = json_decode($res->content());
        $token = $data->data->token;

        $this->assertStringStartsWith('e', $token);

        //Connect successfully.
        $this->json('GET', 'api/user', [], [
            'Authorization' => 'Bearer ' . $token
        ])->see('username@email.com');

        //Tamper token
        $this->json('GET', 'api/user', [], [
            'Authorization' => 'Bearer 3' . $token
        ])->dontSee('username@email.com');
    }
}
