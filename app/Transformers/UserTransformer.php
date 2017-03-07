<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    protected $availableIncludes = [
        'wallets'
    ];

    /**
     * A user transformer
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'phone_number' => $user->phone_number,
            'locked' => $user->otp != null
        ];
    }

    public function includeWallets(User $user)
    {
        $wallets = $user->wallets;

        return $this->collection($wallets, new WalletTransformer());
    }

}
