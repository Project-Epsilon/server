<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = ['title', 'amount', 'wallet_id'];

    /**
     * @return mixed
     */
    public function transactionable()
    {
        return $this->morphTo();
    }
}
