<?php

namespace Tests\Feature;

use App\Transfer;
use App\User;
use App\Wallet;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TransferTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /**
     * A send transfer test.
     *
     * @return void
     */
    public function testSendTransfer()
    {
        $this->seed();

        $user = User::find(1);
        $this->be($user);

        $this->post('api/transfer/user/send', [
            'receiver'  => [
            ],
            'amount' => 1.21,
            'wallet_id' => 1,
            'message' => 'Hello you are a friendly person'
        ])->assertSee('errors');

        $this->post('api/transfer/user/send', [
            'receiver'  => [
                'phone_number' => '15143338888',
            ],
            'amount' => 100.01, //Too much
            'wallet_id' => 1,
            'message' => 'Hello you are a friendly person'
        ])->assertSee('errors');

        $this->post('api/transfer/user/send', [
            'receiver'  => [
                'phone_number' => '15143338888',
            ],
            'amount' => 100,
            'wallet_id' => -1, //Wrong wallet
            'message' => 'Hello you are a friendly person'
        ])->assertSee('errors');

        $this->post('api/transfer/user/send', [
            'receiver'  => [
                'phone_number' => '15143338888',
            ],
            'amount' => 100.001, //To many decimals
            'wallet_id' => 1,
            'message' => 'Hello you are a friendly person'
        ])->assertSee('errors');

        $this->post('api/transfer/user/send', [
            'receiver'  => [
                'phone_number' => '15143338888',
            ],
            'amount' => 100,
            'wallet_id' => 1,
            'message' => 'Hello you are a friendly person'
        ])->assertJsonStructure([
            'data' => ['id', 'sender', 'message']
        ]);

        $this->assertEquals(Wallet::find(1)->balance, '0');
    }

    /**
     * Receive a transfer test.
     *
     * @return @void
     */
    public function testReceiveTransfer()
    {
        $this->seed();

        $transfer = Transfer::create([
            'sender_wallet_id' => 1,
            'amount' => '1000', //10 canadian dollars
            'status' => 'pending',
            'token' => str_random(128)
        ]);

        $user = factory(User::class)->create();
        $this->be($user);

        $this->post('api/transfer/user/receive', [
            'token' => $transfer->token
        ])->assertSee('data');

        $transfer = $transfer->fresh();

        $this->assertEquals('complete', $transfer->status);

        $wallet = $user->wallets()->where('currency_code', 'CAD')->first();

        $this->assertEquals('1000', $wallet->balance);
    }
}
