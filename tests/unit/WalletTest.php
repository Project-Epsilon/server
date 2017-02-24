<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WalletTest extends TestCase{

    use DatabaseMigrations;


    public function testUser(){

       $user = factory(\App\User::class)->create();
       $wallet = factory(\App\Wallet::class)->create();
//       $wallet = new \App\Wallet([
//           'shown' => true,
//           'order' => 1
//       ]);
//
//       $wallet->balance = '50';
//       $wallet->currency_code = 'USD';

       $user->wallets()->save($wallet);

       $wallet = $user->wallets;

       $this->assertEquals(count($wallet), 1);

    }

    public function testTransactions(){

        $wallet = factory(\App\Wallet::class)->create();


//        $wallet = new \App\Wallet([
//            'shown' => true,
//            'order' => 1
//        ]);
//
//        $wallet->balance = '50';
//        $wallet->currency_code = 'USD';
//
//
//        $wallet->save();
//------------------------------------------------
        $transaction = new \App\Transaction([
            'title' => 'title',
            'amount' => '10',
            'wallet_id' => 2,
            'transactionable_id' => 2
        ]);

        $transaction->save();
        //dd($transaction);
        $wallet->transactions()->save($transaction);

        $transactions = $wallet->transactions;

       //dd('hello');
        //dd($transactions);
    }
}