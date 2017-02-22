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
		'sender_wallet_id', 'receiver_wallet_id',
		'amount', 'status', 'created_at', 'token'
	];

	/**
    	 * The attributes that should be hidden for arrays.
    	 *
    	 * @var array
    	 */
	protected $hidden = [
		'id', 'updated_at'
	];

	public function senderWallet() {
		return $this->belongsTo(Wallet::class);
	}

	public function receiverWallet() {
		return $this->belongsTo(Wallet::class);
	}
}
