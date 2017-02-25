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
     * Gets the wallet of that currency from the user.
     *
     * @param Currency $currency
     * @return Wallet: wallet object of the specified currency, or null if the wallet does not exist.
     */
    private function getWalletWithCurrency(Currency $currency)
    {
        return $this->owner->wallets()
            ->where('currency_code', $currency->getCode())
            ->first();
    }

    /**
     * Creates wallet with specified currency.
     *
     * @param Currency $currency
     * @return Wallet: new wallet object with the specified currency.
     */
    private function createWallet(Currency $currency)
    {
        $wallet = new Wallet([
            'shown' => true,
            'order' => $this->owner->wallets()->count() + 1
        ]);

        $wallet->currency_code = $currency->getCode();
        $wallet->balance = '0';

        $this->owner->wallets()->save($wallet);

        return $wallet;
    }

}
