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
        'user_id' => 1,
        'currency_code' => $faker->currencyCode,
        'balance' => $faker->numberBetween(1,12000),
        'shown' => true,
        'order' => $faker->numberBetween(1, 10)
    ];
});

$factory->define(App\Transaction::class, function(Faker\Generator $faker){
    $is_transfer = $faker->boolean(80);

    return [
        'title' => $is_transfer? $faker->name : 'Bank Transfer',
        'amount' => $faker->randomFloat(2, -40, 40),
        'wallet_id' => 1,
        'transactionable_id' => 1,
        'transactionable_type' => $is_transfer? \App\Transfer::class: \App\BankTransfer::class,
        'created_at' => $faker->dateTimeThisYear()
    ];
});

$factory->define(App\Contact::class, function(Faker\Generator $faker){

    return [
        'user_id' => 1,
        'name' => $faker->name,
        'phone_number' => $faker->phoneNumber,
        'email' => $faker->email
    ];
});