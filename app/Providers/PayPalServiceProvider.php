<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PayPalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Add paypal library as singleton
        $this->app->singleton(PayPalServiceProvider::class, function($app){
            return new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    'ATPZcJYf7Ob4M9pH4CSFbpVhqwxW5HH1rntthIiSETmQMQWhDgDDS-1UBP1_PDBlHNjDKaV_8nLyw-3b',     // ClientID
                    'EEzz7scwIqXjyNDsHiuRiaYKYvwJyKRD8Dk7oaAXuiW9K0ngyYFSVKLAhUU-C-dNNVZ3V_iyay70uNyP'      // ClientSecret
                )
            );
        });
    }
}
