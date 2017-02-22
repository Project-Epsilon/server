<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankTransfer extends Model {
	/**
    	 * The attributes that are mass assignable.
    	 *
    	 * @var array
    	 */
	protected $fillable = [
		'method',
		'invoice_id',
		'amount',
		'status',
		'wallet_id'
	];

	/**
    	 * The attributes that should be hidden for arrays.
    	 *
    	 * @var array
    	 */
	protected $hidden = [
		'updated_at'
	];

	public function wallet() {
		return $this->belongsTo(Wallet::class);
	}
}
