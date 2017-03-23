<?php

namespace Tests\Unit;

use App\Transfer;
use App\Wallet;
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
            'token' => str_random(64),
            'message' => 'A pretty message.',
            'sender' => 'Bob Smith',
            'receiver' => 'Recipient Name',
            'amount_display' => '$1.01'
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

        $wallet = factory(Wallet::class)->create([
            'currency_code' => 'CAD'
        ]);

        $transfer = Transfer::create([
            'sender_wallet_id' => 1,
            'receiver_wallet_id' => $wallet->id,
            'amount' => '101',
            'status' => 'pending',
            'token' => str_random(64),
            'sender' => 'Bob Smith',
            'receiver' => 'Recipient Name',
            'amount_display' => '$1.01'
        ]);

        $wallet = Transfer::find(1)->senderWallet;

        $this->assertEquals('CAD', $wallet->currency_code);

        $this->assertNotEmpty($transfer->receiverWallet);
    }
}
