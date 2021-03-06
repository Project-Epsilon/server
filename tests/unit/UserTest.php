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
       $wallet = $user->wallets()->get()->first();

       $this->assertInstanceOf(\App\Wallet::class, $wallet);
    }

    /**
     * Tests bank transfers relationships
     *
     * @return void
     */
    public function testBankTransfers()
    {
        $this->seed();

        $user = \App\User::find(1);

        $this->assertEmpty($user->bankTransfers()->get());
    }

}