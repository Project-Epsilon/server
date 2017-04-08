<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    /**
     * Fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'amount',
        'wallet_id',
        'transactionable_id',
        'transactionable_type'
    ];

    /**
     * @return mixed
     */
    public function transactionable()
    {
        return $this->morphTo();
    }

}
