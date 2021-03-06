<?php

namespace Tests\Feature;

use App\BankTransfer;
use App\User;
use App\Wallet;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BankTransferTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /**
     * A withdrawal test to see the correct deducted amount and checking inputs.
     *
     * @return void
     */
    public function testWithdrawal()
    {
        $this->seed();

        $user = User::find(1);
        $this->be($user);

        $this->post('api/transfer/bank/withdraw', [
            'amount' => 1.01,
            'wallet_id' => 1,
            'email' => 'mail@mail.com'
        ]);

        $wallet = Wallet::find(1);

        $this->assertEquals('9899', $wallet->balance);

        $this->post('api/transfer/bank/withdraw', [
            'amount' => 1.01,
            'wallet_id' => -1,
            'email' => 'mail@mail.com'
        ])->assertSee('error');

        $this->post('api/transfer/bank/withdraw', [
            'amount' => -1.01,
            'wallet_id' => 1,
            'email' => 'mail@mail.com'
        ])->assertSee('error');
    }
    
    /**
     * A deposit test see if paypal returns a url and see if the supported currency is available.
     *
     * @return void
     */
    public function testDeposit()
    {
        $this->seed();

        $user = User::find(1);
        $this->be($user);

        $this->post('api/transfer/bank/deposit', [
            'amount' => '1.01',
            'currency' => 'CAD'
        ])->assertJsonFragment([
            'url'
        ]);

        $this->post('api/transfer/bank/deposit', [
            'amount' => '1.01',
            'currency' => 'DAD'
        ])->assertJsonFragment([
            'errors'
        ]);
    }

    /**
     * Tests of bank transfer query.
     *
     * @return void
     */
    public function testGetTransferTest()
    {
        $this->seed();
        $this->be(User::find(1));

        factory(BankTransfer::class, 3)->create();

        $this->get('api/transfer/bank/transfer')
            ->assertSee('data');

        $this->get('api/transfer/bank/transfer/1')
            ->assertSee('data');

        $this->get('api/transfer/bank/transfer/-1')
            ->assertSee('errors');
    }

}
