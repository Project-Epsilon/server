<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase{

    use DatabaseMigrations;


    /**
     * Test adding wallet to user
     */
    public function testWallets(){

       $this->seed();
       $user = \App\User::find(1);
       $wallet = $user->wallets;

       $this->assertEquals(count($wallet), 1);

    }

    public function testTransactions(){

//        $user = factory(\App\User::class)->create();
//        $wallet = factory(\App\Wallet::class)->make();
//        $user->wallets()->save($wallet);
        //make transaction attach it to wallet
        //assert number of transactions

////------------------------------------------------
//        $transaction = new \App\Transaction([
//            'title' => 'title',
//            'amount' => '10',
//            'wallet_id' => 2,
//            'transactionable_id' => 2
//        ]);
//
//        $transaction->save();
//        //dd($transaction);
//        $wallet->transactions()->save($transaction);
//
//        $transactions = $wallet->transactions;
//
//        //dd($transactions);
    }
}