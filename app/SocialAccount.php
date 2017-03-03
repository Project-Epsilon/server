<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    /**
     * Declare fillable attributes.
     * @var array
     */
    protected $fillable = [
        'social_id',
        'social_provider'
    ];

    /**
     * Disable timestamps
     * @var bool
     */
    public $timestamps = false;

    /**
     * Returns the user associated with this model
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
