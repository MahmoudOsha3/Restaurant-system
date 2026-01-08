<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user , $order ;

    public function __construct($user , $order)
    {
        $this->user = $user ;
        $this->order = $order ;
    }


    public function envelope()
    {
        return new Envelope(
            subject: 'Confirm Order',
        );
    }

    public function content()
    {
        return new Content(
            view: 'pages.website.mail.PaymentMail' ,
        );
    }

    public function attachments()
    {
        return [];
    }
}
