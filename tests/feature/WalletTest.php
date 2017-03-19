<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WalletTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /**
     * A get all wallets tests.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->seed();
        $this->be(User::find(1));

        $this->get('api/wallet')
            ->assertJsonStructure([
                'data' => ['*' => [
                    'id', 'balance'
                ]]
            ]);
    }

    /**
     * A get single wallet test;
     *
     * @return  void
     */
    public function testShow()
    {
        $this->seed();
        $this->be(User::find(1));

        $this->get('api/wallet/1')
            ->assertJsonStructure([
                'data' => ['id', 'balance']
            ]);
    }

}
