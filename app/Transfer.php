<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_wallet_id',
        'receiver_wallet_id',
        'amount',
        'status',
        'token',
        'received_at',
        'receiver_email',
        'receiver_phone_number',
        'message',
        'sender',
        'receiver',
        'amount_display'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'token'
    ];

    /**
     * Attributes that will be casted as date objects.
     *
     * @var array
     */
    protected $dates = [
        'received_at'
    ];

    /**
     * Returns the sender wallet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function senderWallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Returns the receiver wallet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiverWallet()
    {
        return $this->belongsTo(Wallet::class);
    }

}
