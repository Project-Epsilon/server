<?php

namespace App\Classes;

use App\User;
use Money\Money;
use Money\Currency;

class WalletManager
{
    private $owner;

    public function __construct(User $user)
    {
        $this->owner = $user;
    }

    /**
     * Deposits money to ones account
     * @param Money $money
     */
    public function deposit(Money $money)
    {

    }

    /**
     * Withdraws money from ones account
     * @param Money $money
     */
    public function withdraw(Money $money)
    {
        
    }


    private function getWalletWithCurrency(Currency $code)
    {
        
    }
}