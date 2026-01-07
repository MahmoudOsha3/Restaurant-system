<?php

namespace App\Jobs;

use App\Models\Order;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderExpired implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3 ;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        $deletedCount  = Order::where('payment_status' , 'unpaid')
            ->where('created_at' ,'<=' , now()->subDay(1) )->delete();
        Log::info('Order Expired deleted count : ' . $deletedCount );
    }

    public function failed(Exception $exception)
    {
        Log::error('OrderExpired Job failed : ' . $exception->getMessage()) ;
    }
}
