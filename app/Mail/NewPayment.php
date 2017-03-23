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
        return $this->view('email.templates.payments')
            ->with([
                'sender' => ,
                'receiver' => 'Receiver Name',
                'amount' => '$12.32',
                'message' => '',
                'token' => str_random(16)
            ]);
    }
}
