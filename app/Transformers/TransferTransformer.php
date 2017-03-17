<?php

namespace App\Transformers;

use App\Transfer;
use League\Fractal\TransformerAbstract;

class TransferTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Transfer $transfer
     * @return array
     */
    public function transform(Transfer $transfer)
    {
        return [
            'id' => $transfer->id,
            'sender' => $transfer->senderWallet->user()->select('name')->first()->name, //TODO: Performance twice
            'message' => $transfer->message,
            'amount' => $transfer->amount,
            'receiver' => [
                'email' => $transfer->receiver_email,
                'phone_number' => $transfer->receiver_phone_number
            ],
            'status' => $transfer->status,
            'received_at' => $transfer->received_at ? $transfer->received_at->toIso8601String() : '',
            'created_at' => $transfer->created_at->toIso8601String(),
            'updated_at' => $transfer->updated_at->toIso8601String()
        ];
    }
}
