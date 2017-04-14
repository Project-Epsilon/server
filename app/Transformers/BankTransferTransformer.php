<?php

namespace App\Transformers;

use App\BankTransfer;
use League\Fractal\TransformerAbstract;

class BankTransferTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(BankTransfer $transfer)
    {
        return [
            'method' => $transfer->method,
            'invoice_id' => $transfer->invoice_id,
            'amount' => $transfer->amount_display,
            'status' => $transfer->complete,
            'incoming' => (boolean) $transfer->incoming,
            'created_at' => $transfer->created_at->toIso8601String()
        ];
    }
}
