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
            'amount' => 100.01, //To much
            'wallet_id' => 1,
            'message' => 'Hello you are a friendly person'
        ])->assertSee('errors');

        $this->post('api/transfer/user/send', [
            'receiver'  => [
                'phone_number' => '15143338888',
            ],
            'amount' => 100,
            'wallet_id' => 2, //Wrong wallet
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
}
