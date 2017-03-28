<?php

namespace Tests\Unit;

use App\BankTransfer;
use App\Transaction;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TransactionTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Tests polymorphic relationship.
     *
     * @return void
     */
    public function testRelationship()
    {
        $this->seed();

        $transaction = factory(Transaction::class)->create([
            'title' => 'BankTransfer',
            'amount' => '12.32'
        ])->transactionable(new BankTransfer([
            'amount' => '3.12',
            'status' => 'complete',
            'wallet_id' => 1,
            'method' => 'paypal',
            'invoice_id' => '241342'
        ]));

        $this->assertNotEmpty($transaction->transactionable());
    }
}
