<?php

namespace Tests\Feature;

use App\Transfer;
use App\User;
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
                'phone_number' => '15143338888',
            ],
            'amount' => 1.21,
            'wallet_id' => 1,
            'message' => 'Hello you are a friendly person'
        ])->assertSee('errors');

        $this->assertNotNull(Transfer::find(1));
    }
}
