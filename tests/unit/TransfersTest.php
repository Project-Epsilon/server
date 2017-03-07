<?php

namespace Tests\Unit;

use App\Transfer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TransfersTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A unit transfers tests.
     *
     * @return void
     */
    public function testCreateTransfer()
    {
        Transfer::create([
            'sender_wallet_id' => 1,
            'amount' => '101',
            'status' => 'pending',
            'token' => str_random(128),
            'message' => 'A pretty message.'
        ]);

        $this->assertNotNull(Transfer::find(1));
    }

    /**
     *  A relations transfers tests.
     *
     *  @return void
     */
    public function testRelationship()
    {
        $this->seed();

        Transfer::create([
            'sender_wallet_id' => 1,
            'amount' => '101',
            'status' => 'pending',
            'token' => str_random(128)
        ]);

        $wallet = Transfer::find(1)->senderWallet;

        $this->assertEquals('CAD', $wallet->currency_code);
    }
}
