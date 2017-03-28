<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CurrencySeeder::class);

        $this->call(UserSeeder::class);
        $this->call(WalletSeeder::class);
        $this->call(TransactionSeeder::class);
        $this->call(ContactSeeder::class);
    }
}
