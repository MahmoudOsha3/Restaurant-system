<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InsertTask implements ShouldQueue
{
    public function __construct()
    {

    }

    public function handle(OrderCreated $event)
    {
        DB::table('task')->insert([
            'task' => 'Order Created' ,
            'email' => $event->order->user->email
        ]) ;
        Log::info('Order task is finished') ;
    }
}
