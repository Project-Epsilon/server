<?php

namespace App\Mail;

use App\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewPayment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Transfer being sent
     *
     * @var Transfer
     */
    private $transfer;

    /**
     * Create a new message instance.
     *
     * @param Transfer $transfer
     */
    public function __construct(Transfer $transfer)
    {
        $this->transfer = $transfer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $code = $this->transfer->senderWallet->currency_code;

        return $this->view('email.templates.payment')
            ->with([
                'sender' => $this->transfer->sender,
                'receiver' => $this->transfer->receiver,
                'amount' => $this->transfer->amount_display,
                'text' => $this->transfer->message,
                'token' => $this->transfer->token
            ]);
    }
}
