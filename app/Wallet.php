<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    /**
     * Declare fillable attributes.
     * @var array
     */
    protected $fillable = [
       'visible', 'order'
    ];

    /**
     * Returns the user associated with this model
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}