<?php
namespace App\Classes;

use Illuminate\Notifications\Messages\NexmoMessage as Message;

class NexmoMessage extends Message
{
    /**
     * Recipient phone number
     *
     * @var
     */
    public $to;

    /**
     * Creates a new message instance.
     *
     * NexmoMessage constructor.
     * @param string $content
     */
    public function __construct($content = '')
    {
        parent::__construct($content);
    }

    /**
     * Sets the recipient phone number.
     *
     * @param $to
     * @return $this
     */
    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

}