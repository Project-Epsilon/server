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
     * Deposits money into the user's account.
     *
     * @param Money $money: object representing the amount to be deposited.
     * @return Wallet: wallet object that the money was deposited to.
     */
    public function deposit(Money $money)
    {
        $wallet = $this->getWalletWithCurrency($money->getCurrency());
        // Create wallet if it does not exist.
        if (!$wallet) {
            $wallet = $this->createWallet($money->getCurrency());
        }

        $current_balance = new Money($wallet->balance, $money->getCurrency());
        $new_balance = $current_balance->add($money);
        $wallet->balance = $new_balance->getAmount();

        $wallet->save();
        return $wallet;
    }

    /**
     * Withdraws money from the user's account.
     *
     * @param Money $money: object representing the amount to withdraw.
     * @return Wallet: wallet object that the money was deposited to, or null if wallet has insufficient funds.
     */
    public function withdraw(Money $money)
    {
        $wallet = $this->getWalletWithCurrency($money->getCurrency());
        if (!$wallet) return null;

        $current_balance = new Money($wallet->balance, $money->getCurrency());
        if ($money->greaterThan($current_balance)) return null;

        $new_balance = $current_balance->subtract($money);
        $wallet->balance = $new_balance->getAmount();

        $wallet->save();
        return $wallet;
    }

    /**
     * Gets the wallet of that currency f
     *
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
