<?php

namespace App\Transformers;

use App\Wallet;
use League\Fractal\TransformerAbstract;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;

class WalletTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'transactions'
    ];

    /**
     * A user wallet transformer
     *
     * @param Wallet $wallet
     * @return array
     */
    public function transform(Wallet $wallet)
    {
        $money = new Money($wallet->balance, new Currency($wallet->currency_code));
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return [
            'id' => $wallet->id,
            'user_id' => $wallet->user_id,
            'balance' => $formatter->format($money),
            'visible' => $wallet->shown,
            'currency_code' => $wallet->currency_code,
            'order' => $wallet->order,
            'created_at' => $wallet->created_at->toIso8601String()
        ];
    }

    /**
     * Includes transactions
     *
     * @param Wallet $wallet
     * @return \League\Fractal\Resource\Collection
     */
    public function includeTransactions(Wallet $wallet)
    {
        $transactions = $wallet->transactions;

        return $this->collection($transactions, new TransactionTransformer());
    }

}
