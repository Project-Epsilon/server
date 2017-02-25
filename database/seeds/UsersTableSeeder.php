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
        //$user =
        factory(App\User::class)
            ->create()
            ->each(function($u){
                $u->wallets()->save(factory(App\Wallet::class)->make());
            });

//        $wallet = new \App\Wallet([
//            'shown' => true,
//            'order' => 1
//        ]);
//
//        $wallet->balance = '10';
//        $wallet->currency_code = 'USD';
//        $user->wallets()->save($wallet);

    }

}
