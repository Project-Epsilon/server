<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model {
	/**
    	 * The attributes that are mass assignable.
    	 *
    	 * @var array
    	 */
	protected $fillable = [
		'id', 'sender_id', 'receiver_id', 'amount', 'status',
		'created_at'
	];

	/**
    	 * The attributes that should be hidden for arrays.
    	 *
    	 * @var array
    	 */
	protected $hidden = [
		'updated_at'
	];
}
