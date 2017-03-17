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
     *
     * @return void
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
     *
     * @return void
     */
    public function testWithdraw()
    {
        $this->seed();
        $user = User::find(1);
        $walletManager = new WalletManager($user);

        $wallet = $walletManager->withdraw(new Money(200, new Currency('TEST')));
        $this->assertNull($wallet);

        $walletManager->deposit(new Money(200, new Currency('TEST')));
        $wallet = $walletManager->withdraw(new Money(250, new Currency('TEST')));
        $this->assertNull($wallet);

        $wallet = $walletManager->withdraw(new Money(200, new Currency('TEST')));
        $balance = $wallet->balance;

        $this->assertEquals($balance, 0);
    }

    /**
     * Tests if amount has the right number of decimals
     *
     * return @void
     */
    public function testDecimalCheck()
    {
        $this->seed();
        $user = User::find(1);

        $manager = new WalletManager($user);

        $true = $manager->hasCorrectDecimalPlaces('1.10', \App\Currency::find('CAD'));
        $this->assertEquals(true, $true);

        $false = $manager->hasCorrectDecimalPlaces('1.102', \App\Currency::find('CAD'));
        $this->assertEquals(false, $false);

        $true = $manager->hasCorrectDecimalPlaces('1.3', \App\Currency::find('CAD'));
        $this->assertEquals(true, $true);
    }

}
