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
            'sender' => $transfer->sender,
            'message' => $transfer->message,
            'amount' => $transfer->amount_display,
            'receiver' => [
                'name' => $transfer->receiver,
                'email' => $transfer->receiver_email,
                'phone_number' => $transfer->receiver_phone_number
            ],
            'sender_wallet_id' => $transfer->sender_wallet_id,
            'receiver_wallet_id' => $transfer->receiver_wallet_id,
            'status' => $transfer->status,
            'received_at' => $transfer->received_at ? $transfer->received_at->toIso8601String() : '',
            'created_at' => $transfer->created_at->toIso8601String(),
            'updated_at' => $transfer->updated_at->toIso8601String()
        ];
    }
}
