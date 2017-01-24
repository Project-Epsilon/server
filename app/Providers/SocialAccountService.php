<?php

namespace App\Providers;

use App\SocialAccount;
use App\User;
use Illuminate\Support\ServiceProvider;

class SocialAccountService extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SocialAccountService::class, function ($app) {
            return new SocialAccountService($app);
        });
    }

    /**
     * Returns the appropriate user associated with the social account.
     *
     * @param $socialUser
     * @param $provider
     * @return \App\User
     */
    public function getUser($socialUser, $provider)
    {
        $account = SocialAccount::where('social_id', $socialUser->getId())
            ->where('social_provider', $provider)
            ->first();

        if ($account) {
            return $account->user;
        }

        $account = new SocialAccount([
            'social_id' => $socialUser->getId(),
            'social_provider' => $provider
        ]);

        $user = User::where('email', $socialUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail()
            ]);
        }

        //Associate social account with user
        $account->user()->associate($user);
        $account->save();

        return $user;
    }
}
