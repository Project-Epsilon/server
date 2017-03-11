<?php

use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Wallet::class)->create([
            'currency_code' => 'CAD',
            'user_id' => 1,
            'balance' => 10000
        ]);

        factory(\App\Wallet::class, 2)->create([
            'user_id' => 1
        ]);
    }
}
