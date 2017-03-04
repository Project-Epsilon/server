<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WalletManagerTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Test for depositing funds into wallet
     */
    public function testDeposit()
    {
        $this->seed();
        $user = \App\User::find(1);
        $walletManager = new \App\Classes\WalletManager($user);
        $walletManager->deposit(new \Money\Money(200, new \Money\Currency('TEST')));
        $wallet = $user->wallets()->where('currency_code', 'TEST')->first();
        $this->assertEquals($wallet->balance, 200);
    }

    /**
     * Test for withdrawing funds from wallet (3 assertions)
     */
    public function testWithdraw()
    {
        $this->seed();
        $user = \App\User::find(1);
        $walletManager = new \App\Classes\WalletManager($user);

        //1)Withdrawing without having a wallet of that currency
        $wallet = $walletManager->withdraw(new \Money\Money(200, new \Money\Currency('TEST')));
        $this->assertNull($wallet);

        //2)Withdrawing more funds than wallet contains
        $walletManager->deposit(new \Money\Money(200, new \Money\Currency('TEST')));
        $wallet = $walletManager->withdraw(new \Money\Money(250, new \Money\Currency('TEST')));
        $this->assertNull($wallet);

        //3)Withdrawing less than or equal funds than wallet contains
        $wallet = $walletManager->withdraw(new \Money\Money(200, new \Money\Currency('TEST')));
        $balance = $wallet->balance;
        $this->assertEquals($balance, 0);
    }
}
