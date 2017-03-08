<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'emai' => 'user@user.com',
            'password' => bcrypt('password'),
            'name' => 'Bob Stanley'
        ]);

        //Create wallet
        $user = factory(App\User::class)
            ->create([
                'email' => 'user@user.com',
                'password' => bcrypt('password')
            ]);

        //Create a wallet with $100 currency.
        $wallet = factory(\App\Wallet::class)->make([
            'balance' => '10000', //100 hundred dollar,
            'currency_code' => 'CAD'
        ]);

        $user->wallets()->save($wallet);
    }


}
