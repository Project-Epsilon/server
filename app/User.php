<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'username', 'otp'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'updated_at', 'otp'
    ];

    /**
     * Returns the wallet relationship of this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * Returns the contact relationship of this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Transfers the user had sent out;
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function transfersOut()
    {
        return $this->hasManyThrough(Transfer::class, Wallet::class, 'user_id', 'sender_wallet_id');
    }

    /**
     * Transfers the user had received;
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function transfersIn()
    {
        return $this->hasManyThrough(Transfer::class, Wallet::class, 'user_id', 'receiver_wallet_id');
    }

    /**
     * Bank transfers the user has made.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function bankTransfers()
    {
        return $this->hasManyThrough(BankTransfer::class, Wallet::class);
    }

}
