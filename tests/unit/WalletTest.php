<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WalletTest extends TestCase{

    use DatabaseMigrations;

    /**
     * test to see if wallet belongs to a class of type user
     */
    public function testUser()
    {
        $this->seed();
        $wallet = \App\Wallet::find(1);
        $user = $wallet->user;

        $this->assertNotNull($user);
    }

    public function testTransactions()
    {

    }


}