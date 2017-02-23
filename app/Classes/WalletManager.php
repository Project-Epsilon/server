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
        $currency = $money->getCurrency();
        $wallet = $this->getWalletWithCurrency($currency);

        if(! $wallet)
            $wallet = $this->createWallet($currency);

        $current = new Money($wallet->balance, new Currency($wallet->currency_code));
        $wallet->balance = $current->add($money)->getAmount();
        return $wallet->save();
    }

    /**
     * Withdraws money from ones account
     * @param Money $money
     * @return boolean - true if successful
     */
    public function withdraw(Money $money)
    {
        $currency = $money->getCurrency();
        $wallet = $this->getWalletWithCurrency($currency);
        if(!$wallet)
        {
            return false;
        }
        else {
            $current = new Money($wallet->balance, new Currency($wallet->currency_code));
            $wallet->balance = $current->subtract($money)->getAmount();
            return $wallet->save();
        }
        //find that wallet. if exists return true or false
    }

    /**
     * Gets the wallet of that currency from the database.
     * @param Currency $currency
     * @return Wallet|null - null if not found
     */
    private function getWalletByCurrency(Currency $currency)
    {
        //find user wallet with that currency. if not, create one with that currency
        //$wallet = wallets()->where('$currency_code', 'USD')->first();
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