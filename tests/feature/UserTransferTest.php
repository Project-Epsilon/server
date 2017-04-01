<?php

namespace Tests\Feature;

use App\Transfer;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTransferTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /**
     * A get all transfers tests.
     *
     * @return void
     */
    public function testGetAllTransfer()
    {
        $this->seed();

        $user = User::find(1);
        $this->be($user);

        $wallet = $user->wallets()->first();

        $wallet->transfersOut()
            ->save(factory(Transfer::class)->make());
        $wallet->transfersIn()
            ->save(factory(Transfer::class)->make([
                'receiver' => 'Bob'
            ]));

        $this->get('api/transfer/user/transfer')
            ->assertSee('data');
    }

    /**
     * Get a transfer by token
     *
     * @return void
     */
    public function testGetTransferByToken()
    {
        $this->seed();

        $token = factory(Transfer::class)->create()->token;

        $this->post('api/transfer/user', [
            'token' => $token
        ])->assertSee('data');
    }

    /**
     * Gets a transfer by id.
     *
     * @return void
     */
    public function testGetATransferTest()
    {
        $this->seed();
        $this->be(User::find(1));

        $transfer = factory(Transfer::class)->create([
            'sender_wallet_id' => 1
        ]);

        $this->get('api/transfer/user/transfer/' . $transfer->id)
            ->assertSee('data');
    }

    /**
     * Pending out transfers tests
     *
     * @return void
     */
    public function testPendingOut()
    {
        $this->seed();
        $this->be(User::find(1));

        factory(Transfer::class, 2)->create([
            'sender_wallet_id' => 1,
            'status' => 'pending'
        ]);

        $this->get('api/transfer/user/out')
            ->assertSee('data');
    }

}

