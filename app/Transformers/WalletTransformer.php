<?php

namespace App\Transformers;

use App\Wallet;
use League\Fractal\TransformerAbstract;

class WalletTransformer extends TransformerAbstract
{
    /**
     * A user wallet transformer
     *
     * @param Wallet $wallet
     * @return array
     */
    public function transform(Wallet $wallet)
    {
        return [
            'id' => $wallet->id,
            'user_id' => $wallet->user_id,
            'balance' => $wallet->balance, //Needs adjustments,
            'visible' => $wallet->shown,
            'currency_code' => $wallet->currency_code,
            'order' => $wallet->order,
            'created_at' => $wallet->created_at->toDateTimeString()
        ];
    }
}
