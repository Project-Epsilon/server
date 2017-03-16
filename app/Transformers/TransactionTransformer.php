<?php

namespace App\Transformers;

use App\BankTransfer;
use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'id' => $transaction->id,
            'title' => $transaction->title,
            'wallet_id' => $transaction->wallet_id,
            'transaction_id' => $transaction->transactionable_id,
            'transaction_type' => $transaction->transactionable_type == BankTransfer::class ? 'bank' : 'transfer' ,
            'amount' => $transaction->amount,
            'created_at' => $transaction->created_at->toIso8601String()
        ];
    }
}
