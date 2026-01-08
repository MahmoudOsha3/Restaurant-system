<?php

namespace App\Jobs;

use App\Mail\PaymentMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2 ;
    public $timeout = 120 ;

    public $user , $order ;
    public function __construct($user , $order)
    {
        $this->user = $user ;
        $this->order = $order ;
    }

    public function handle()
    {
        Mail::to($this->user->email)->send(new PaymentMail($this->user , $this->order));
    }

    public function failed()
    {
        Log::error('Sent Mail Payment failed : ' . $this->user->name . ', email : ' . $this->user->email ) ;
    }
}
