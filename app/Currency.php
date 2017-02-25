<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
     * Disable timestamps
     * @var bool
     */
    public $timestamps = false;

    /**
     * Change primary key to code
     * @var string
     *
     */
    protected $primaryKey = 'code';

    /**
     * Disable incrementing
     * @var bool
     */
    public $incrementing = false;

}
