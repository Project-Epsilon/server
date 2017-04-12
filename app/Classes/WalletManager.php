<?php

namespace App\Classes;

use App\BankTransfer;
use App\Transaction;
use App\Transfer;
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

        if (! $wallet) {
            $wallet = $this->createWallet($money->getCurrency());
        }

        $balance = $wallet->toMoney();

        $wallet->balance = $balance->add($money)->getAmount();
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

        if (! $wallet)
            return null;

        $balance = $wallet->toMoney();

        if (! $this->hasEnoughFunds($money, $balance))
            return null;

        $wallet->balance = $balance->subtract($money)->getAmount();
        $wallet->save();

        return $wallet;
    }

    /**
     * Records the transfer to the wallet.
     *
     * @param $wallet Wallet
     * @param $transfer BankTransfer|Transfer
     * @param $incoming bool
     */
    public function record($transfer, Wallet $wallet, $incoming)
    {
        $title = get_class($transfer) == BankTransfer::class ? 'Bank Transfer' :
            ($incoming ? $transfer->sender : $transfer->receiver);

        $currency = $wallet->currency;
        $amount = $currency->toDecimal($transfer->amount);

        $wallet->transactions()->save(new Transaction([
            'title' => $title,
            'amount' => $incoming ? $amount : '-' . $amount,
            'transactionable_id' => $transfer->id,
            'transactionable_type' => get_class($transfer)
        ]));
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

    /**
     * Validates the withdrawal amount from a wallet.
     *
     * @param $id - Id of the wallet
     * @param $amount - A floating point number describing the amount
     * @return Money|string
     */
    public function validateWithdrawalFromWallet($id, $amount)
    {
        if (! $wallet = $this->getWalletWithId($id)) {
            return 'Wallet does not exists.';
        }

        if (! $this->hasCorrectDecimalPlaces($amount, $wallet->currency)){
            return 'Amount has too many decimals.';
        }

        $withdrawal = $this->convertAmountToMoney($amount, $wallet->currency);

        if(! $this->hasEnoughFunds($withdrawal, $wallet->toMoney())){
            return 'Not enough funds.';
        }

        return $withdrawal;
    }

    /**
     * Gets the wallet by id from the specific owner.
     *
     * @param $id
     * @return mixed
     */
    public function getWalletWithId($id)
    {
        return $this->owner->wallets()->where('id', $id)->first();
    }

    /**
     * Checks if the wallet has enough funds.
     *
     * @param Money $money
     * @param Money $wallet
     * @return bool
     */
    public function hasEnoughFunds(Money $money, Money $wallet)
    {
        if (! $money->isSameCurrency($wallet)){
            return false;
        }

        return $wallet->greaterThanOrEqual($money);
    }

    /**
     * Converts amount of Money
     * @param $amount
     * @param \App\Currency $currency
     * @return Money
     */
    public function convertAmountToMoney($amount, \App\Currency $currency)
    {
        return new Money($currency->toInteger($amount), new Currency($currency->code));
    }


    /**
     * Checks if the amount in floating point has the correct amount of decimal points.
     *
     * @param $amount
     * @param \App\Currency $currency
     * @return bool
     */
    public function hasCorrectDecimalPlaces($amount, \App\Currency $currency)
    {
        $integer = $currency->toInteger($amount);

        return abs((((int) $integer) - $integer)) < 0.00001 ;
    }

}
