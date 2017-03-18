<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase{

    use DatabaseMigrations;


    /**
     * Test to return wallet associated with user
     *
     * @return void
     */
    public function testWallets()
    {
       $this->seed();

       $user = \App\User::find(1);
       $wallet = $user->wallets->first();

       $this->assertInstanceOf(\App\Wallet::class, $wallet);
    }
}