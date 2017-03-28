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
        factory(App\Transaction::class, rand(35, 50))->create([
            'wallet_id' => 1
        ]);
        factory(App\Transaction::class, rand(15, 50))->create([
            'wallet_id' => 2
        ]);
        factory(App\Transaction::class, rand(25, 50))->create([
            'wallet_id' => 3
        ]);
    }
}
