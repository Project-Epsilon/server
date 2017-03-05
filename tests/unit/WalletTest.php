<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WalletTest extends TestCase{

    use DatabaseMigrations;

    /**
     * Test to return user associated with wallet
     *
     * @return void
     */
    public function testUser()
    {
        $this->seed();

        $wallet = \App\Wallet::find(1);
        $user = $wallet->user;

        $this->assertInstanceOf(\App\User::class, $user);
    }

    /**
     * Test to return transaction associated with wallet
     *
     * @return void
     */
    public function testTransactions()
    {
        $this->seed();

        $wallet = \App\Wallet::find(1);
        $transaction = $wallet->transactions->first();

        $this->assertInstanceOf(\App\Transaction::class, $transaction);
    }
}