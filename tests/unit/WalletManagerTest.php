<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Classes\WalletManager;
use Money\Money;
use Money\Currency;
use App\User;
use App\Wallet;
use App\Transaction;

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

        $old_balance = $user->wallets()->where('currency_code',  'USD')->first()->balance;

        $walletManager = new WalletManager($user);
        $walletManager->deposit(new Money('200', new Currency('USD')));

        $wallet = $user->wallets()->where('currency_code', 'USD')->first();

        $this->assertEquals($wallet->balance, ($old_balance? : 0) + 200);
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
     * Test the validate withdrawal method.
     */
    public function testValidateWithdrawalFromWallet()
    {
        $this->seed();
        $user = User::find(1);

        $manager = new WalletManager($user);

        $withdrawal = $manager->validateWithdrawalFromWallet(1, 100);

        $this->assertInstanceOf(Money::class, $withdrawal);

        $withdrawal = $manager->validateWithdrawalFromWallet(1, 100.01);

        $this->assertTrue(is_string($withdrawal));

        $withdrawal = $manager->validateWithdrawalFromWallet(1, 100.001);

        $this->assertTrue(is_string($withdrawal));
    }


    /**
     * Test to see if the wallet manager is getting only the user's wallets.
     */
    public function testGetWalletWithId()
    {
        $this->seed();
        $user = User::find(1);

        $manager = new WalletManager($user);

        factory(\App\Wallet::class)->create(['user_id' => 2]); //Wallet id is 4

        $wallet = $manager->getWalletWithId(4);
        $this->assertEmpty($wallet);
    }

    /**
     * Test the has enough funds method.
     *
     * @return void
     */
    public function testHasEnoughFunds()
    {
        $this->seed();
        $user = User::find(1);

        $manager = new WalletManager($user);

        $wallet = $user->wallets()->first()->toMoney();

        $amount = Money::CAD(10000); //One hundred dollars.
        $true = $manager->hasEnoughFunds($amount, $wallet);

        $this->assertTrue($true);

        $amount = Money::CAD(10001); //One hundred dollars and one cent.
        $false = $manager->hasEnoughFunds($amount, $wallet);

        $this->assertFalse($false);

        $amount = Money::USD(10000); //One hundred dollars and one cent.
        $false = $manager->hasEnoughFunds($amount, $wallet);

        $this->assertFalse($false);
    }

    /**
     * Tests the convert to amount money method.
     *
     * @return void
     */
    public function testConvertAmountToMoney()
    {
        $this->seed();
        $user = User::find(1);

        $manager = new WalletManager($user);
        $money = $manager->convertAmountToMoney(2.44, \App\Currency::find('CAD'));

        $this->assertEquals('244', $money->getAmount());
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
        $this->assertTrue($true);

        $false = $manager->hasCorrectDecimalPlaces('1.102', \App\Currency::find('CAD'));
        $this->assertFalse($false);

        $true = $manager->hasCorrectDecimalPlaces('1.3', \App\Currency::find('CAD'));
        $this->assertTrue($true);
    }

    /**
     * Tests transfer recording.
     *
     * return @void
     */
    public function testRecordTransfer()
    {
        $this->seed();
        $user = User::find(1);

        $manager = new WalletManager($user);

        $transfer = \App\Transfer::create([
            'sender_wallet_id' => 1,
            'amount' => '1000', //10 canadian dollars
            'status' => 'pending',
            'token' => str_random(128),
            'sender' => 'Bob Smith',
            'receiver' => 'Recipient Name',
            'amount_display' => '10.00'
        ]);

        $manager->record($transfer, Wallet::find(1), true);

        $transaction = Transaction::where('title', $transfer->sender)->first();
        $this->assertEquals($transaction->title, $transfer->sender);

        $manager->record($transfer, Wallet::find(1), false);

        $transaction = Transaction::where('title', $transfer->receiver)->first();
        $this->assertEquals($transaction->title, $transfer->receiver);
    }

}
