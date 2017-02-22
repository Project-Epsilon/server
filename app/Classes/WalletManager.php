<?php

namespace App\Classes;

use App\User;
use App\Wallet;
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
     * @return boolean - true if successful
     */
    public function deposit(Money $money)
    {
        $deposit_amount = (string)$money->getamount();
        /**
         * Do something with deposit amount
         * */

        return true;
    }

    /**
     * Withdraws money from ones account
     * @param Money $money
     * @return boolean - true if successful
     */
    public function withdraw(Money $money)
    {

        $withdraw_amount = (string)$money->getamount();
        /**
         * Do something with withdrawl amount
         * */
    }

    /**
     * Gets the wallet of that currency from the database.
     * @param Currency $currency
     * @return Wallet|null - null if not found
     */
    private function getWalletWithCurrency(Currency $currency)
    {
        
    }

    /**
     * Creates wallet will that currency
     * @param Currency $currency
     * @return Wallet
     */
    private function createWallet(Currency $currency)
    {

    }

}