<?php

namespace Tests\Feature;

use App\User;
use App\Wallet;
use Illuminate\Database\Eloquent\Model;
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
        $this->seed(\UserSeeder::class);

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

    /**
     * Tests delete account.
     *
     * @return void
     */
    public function testDeleteAccount()
    {
        $this->seed();

        $user = User::find(1);
        $this->be($user);

        $this->delete('api/user')
            ->assertSee('errors');

        $user->wallets()->update(['balance' => '0']);

        $this->delete('api/user')
            ->assertSee('ok');
    }
}
