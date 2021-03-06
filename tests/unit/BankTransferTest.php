<?php

namespace Tests\Unit;

use App\BankTransfer;
use App\Transaction;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BankTransferTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test wallet relationship.
     *
     * @return void
     */
    public function testWalletRelationship()
    {
        BankTransfer::unguard();

        $transfer = BankTransfer::create([
            'amount' => '3.12',
            'amount_display' => '$3.12 CAD',
            'status' => 'complete',
            'wallet_id' => 1,
            'method' => 'paypal',
            'invoice_id' => '241342',
            'incoming' => true
        ]);

        $wallet = $transfer->wallet();

        $this->assertNotEmpty($wallet);
    }
}
