<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'phone_number' => $faker->phoneNumber
    ];
});

$factory->define(App\Wallet::class, function(Faker\Generator $faker){

    return [
        'shown' => true,
        'order' => 1,
        'currency_code' => 'USD',
        'balance' => $faker->numberBetween(1), //not string?
        'user_id' => $faker->numberBetween(1)
    ];
});

$factory->define(App\Transaction::class, function(Faker\Generator $faker){

    return [
        'wallet_id' => 1,
        'amount' => '50',
        'transactionable_id' => 2
    ];
});
