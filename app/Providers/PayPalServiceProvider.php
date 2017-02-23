<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class PayPalServiceProvider extends ServiceProvider
{
    private $context;
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
        $this->app->singleton(PayPalServiceProvider::class, function ($app) {
            $this->context =  new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    config('services.paypal.client_id'),     // ClientID
                    config('services.paypal.client_secret')      // ClientSecret
                )
            );

            $this->context->setConfig([
                'mode' => config('app.debug')? 'sandbox': 'live',
                // 'http.CURLOPT_CONNECTTIMEOUT' => 30
                // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
                //'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
            ]);

            return $this;
        });
    }

    public function getContext()
    {
        return $this->context;
    }

}
