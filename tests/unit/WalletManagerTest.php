<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Classes\WalletManager;
use Money\Money;
use Money\Currency;
use App\User;

class WalletManagerTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Test for depositing funds into wallet
     */
    public function testDeposit()
    {
        $this->seed();

        $user = User::find(1);

        $walletManager = new WalletManager($user);
        $walletManager->deposit(new Money('200', new Currency('USD')));

        $wallet = $user->wallets()->where('currency_code', 'USD')->first();

        $this->assertEquals($wallet->balance, '200');
    }

    /**
     * Test for withdrawing funds from wallet (3 assertions)
     */
    public function testWithdraw()
    {
        $this->seed();
        $user = User::find(1);
        $walletManager = new WalletManager($user);

        //1)Withdrawing without having a wallet of that currency
        $wallet = $walletManager->withdraw(new Money(200, new Currency('TEST')));
        $this->assertNull($wallet);

        //2)Withdrawing more funds than wallet contains
        $walletManager->deposit(new Money(200, new Currency('TEST')));
        $wallet = $walletManager->withdraw(new Money(250, new Currency('TEST')));
        $this->assertNull($wallet);

        //3)Withdrawing less than or equal funds than wallet contains
        $wallet = $walletManager->withdraw(new Money(200, new Currency('TEST')));
        $balance = $wallet->balance;

        $this->assertEquals($balance, 0);
    }
}
