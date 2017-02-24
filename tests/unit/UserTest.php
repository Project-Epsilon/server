<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase{

    use DatabaseMigrations;


    /**
     * Test validating existence of user wallets
     */
    public function testWallets(){

       $this->seed();
       $user = \App\User::find(1);
       $wallet = $user->wallets;

       $this->assertEquals(count($wallet), 1);

    }
}