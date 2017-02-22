<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model {
	/**
    	 * The attributes that are mass assignable.
    	 *
    	 * @var array
    	 */
	protected $fillable = [
		'name', 'email', 'phone_number', 'created_at'
	];

	/**
    	 * The attributes that should be hidden for arrays.
    	 *
    	 * @var array
    	 */
	protected $hidden = [
		'id', 'updated_at'
	];
}
