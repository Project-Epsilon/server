<?php

namespace App\Jobs;

use App\Classes\NexmoMessage;
use App\Mail\NewPayment;
use App\Providers\NexmoServiceProvider;
use App\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The transfer to send
     *
     * @var Transfer
     */
    private $transfer;

    /**
     * Create a new job instance.
     *
     * @param Transfer $transfer
     */
    public function __construct(Transfer $transfer)
    {
        $this->transfer = $transfer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(NexmoServiceProvider $nexmo)
    {
        $amount = $this->transfer->amount_display;
        $email = $this->transfer->receiver_email;
        $phone_number = $this->transfer->receiver_phone_number;
        $code = $this->transfer->senderWallet->currency_code;

        if ($email){
            Mail::to($email)->send(new NewPayment($this->transfer));
        }

        $link = config('app.transfer_link') . $this->transfer->token;

        if ($phone_number) {
            $message = (new NexmoMessage())
                ->content('You\'ve got a new payment of ' . $amount . ' ' . $code. '. ' . $link . ' ')
                ->to($phone_number);

            try {
                $nexmo->send($message);
            } catch (\Exception $e){ }
        }
    }

}
