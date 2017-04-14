<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic login test.
     *
     * @return void
     */
    public function testLogin()
    {
        $this->seed();

        $this->post('api/login', [
            'email' => 'user@user.com',
            'password' => 'password'
        ])->assertSee('data');
    }

    /**
     * Test to see if login url can be requested.
     *
     * @return @void
     */
    public function testRedirectSocial()
    {
        $this->seed();

        $this->get('api/auth/google')
            ->assertJsonStructure(['data' => ['*' => 'url']]);

        $this->get('api/auth/something')
            ->assertSee('errors');
    }

    /**
     * Test logout.
     *
     * @return @void
     */
    public function testLogout()
    {
        $this->seed();

        $response = $this->post('api/login', [
            'email' => 'user@user.com',
            'password' => 'password'
        ])->decodeResponseJson();

        $token = $response['meta']['token'];

        $this->get('api/user', [
            'Authorization' => 'Bearer ' . $token
        ])->assertSee('data');

        $this->get('api/logout', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $this->get('api/user', [
            'Authorization' => 'Bearer ' . $token
        ])->assertSee('error');
    }

}
