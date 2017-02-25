<?php

use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wallet = \App\Wallet::find(1);
        $wallet->transactions()->save(factory(App\Transaction::class)->make());
    }
}
