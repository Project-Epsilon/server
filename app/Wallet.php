<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Money\Money;

class Wallet extends Model
{
    /**
     * Declare fillable attributes.
     * @var array
     */
    protected $fillable = [
       'shown', 'order'
    ];

    /**
     * Returns the user associated with this model
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return the transactions associated with the wallet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Returns the currency relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code');
    }

    /**
     * Returns the transfer out relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transfersOut()
    {
        return $this->hasMany(Transfer::class, 'sender_wallet_id');
    }

    /**
     * Returns the transfer in relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transfersIn()
    {
        return $this->hasMany(Transfer::class, 'receiver_wallet_id');
    }

    /**
     * Converts the wallet to a Money instance.
     *
     * @return Money
     */
    public function toMoney()
    {
        $currency = $this->currency_code;
        return Money::$currency($this->balance);
    }
}