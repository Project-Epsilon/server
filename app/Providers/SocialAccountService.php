<?php

namespace App\Providers;

use App\Classes\SocialUser\FacebookUser;
use App\Classes\SocialUser\GoogleUser;
use App\SocialAccount;
use App\User;
use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Token;

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
     * @param Token $token
     * @return User
     */
    public function getUser(Token $token)
    {
        $claims = $token->getClaims();

        $account = SocialAccount::where('social_id', $claims['sub'])->first();

        if ($account) {
            return $account->user;
        }

        $account = new SocialAccount([
            'social_id' => $claims['sub']
        ]);

        $socialUser = $this->getSocialUser($claims);

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

    /**
     * Returns the SocialUser.
     * @param $claims
     * @return FacebookUser|GoogleUser|null
     */
    protected function getSocialUser($claims)
    {
        $provider = explode('|', $claims['sub']);

        switch ($provider[0]){
            case 'facebook':
                return new FacebookUser($claims);
            case 'google-oauth2':
                return new GoogleUser($claims);
        }

        return null;
    }

}
