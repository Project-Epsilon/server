<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WalletTest extends TestCase{

    use DatabaseMigrations;

    /**
     * Test to see if wallet belongs to Transaction class
     */
    public function testUser()
    {
        $this->seed();
        $wallet = \App\Wallet::find(1);
        $user = $wallet->user;

        $this->assertNotNull($user);
    }

    /**
     * Test to see if transaction belongs to Wallet class
     */
    public function testTransactions()
    {
        $this->seed();
        $wallet = \App\Wallet::find(1);
        $transaction = $wallet->transactions;

        $this->assertNotNull($transaction);
    }
}