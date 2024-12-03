<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CardEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'Michael@tremstack.com';
        $subject = 'Card Notification';
        $name = 'Structure Compliance Group';

        return $this->view('emails.card')
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject)
            ->with([ 'test_message' => $this->data['message'] ]);
        //return $this->view('view.name');
    }
}
