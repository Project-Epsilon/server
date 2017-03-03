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
            'email' => $user->email,
            'username' => $user->username,
            'phone_number' => $user->phone_number
        ];
    }

    public function includeWallets(User $user)
    {
        $wallets = $user->wallets;

        return $this->collection($wallets, new WalletTransformer());
    }

}
