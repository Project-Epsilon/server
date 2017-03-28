<?php

namespace App\Providers;

use App\Classes\NexmoMessage;
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
     * @param NexmoMessage $message
     */
    public function send(NexmoMessage $message)
    {
        $this->client->message()->send([
            'to' => $message->to,
            'from' => config('services.nexmo.sms_from'),
            'text' => $message->content
        ]);
    }
}
