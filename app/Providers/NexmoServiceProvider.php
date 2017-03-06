<?php

namespace App\Providers;

use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Support\ServiceProvider;
use Nexmo\Client;
use Nexmo\Client\Credentials\Basic;

class NexmoServiceProvider extends ServiceProvider
{
    /**
     * Nexmo Client
     *
     * @var
     */
    private $client;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(NexmoServiceProvider::class, function ($app) {
            $this->client = new Client(new Basic(config('services.nexmo.key'), config('services.nexmo.secret')));

            return $this;
        });
    }

    /**
     * Sends message to the receiver phone number
     *
     * @param $content
     * @param $receiver
     */
    public function send($content, $receiver)
    {
        $this->client->message()->send([
            'to' => $receiver,
            'from' => config('services.nexmo.sms_from'),
            'text' => $content
        ]);
    }
}
