<?php

namespace Tests\Feature;

use App\BankTransfer;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BankTransferTransactionTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /**
     * Get all bank transfers.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->seed();
        $this->be(User::find(1));

        $this->get('api/transfer/bank/transfer')
            ->assertSee('data');
    }

    /**
     * Get a single bank transfer
     */
    public function testShow()
    {
        $this->seed();
        $this->be(User::find(1));

        factory(BankTransfer::class)->create();
        $this->get('api/transfer/bank/transfer/1')
            ->assertSee('data');

        $this->get('api/transfer/bank/transfer/0')
            ->assertSee('errors');
    }
}
