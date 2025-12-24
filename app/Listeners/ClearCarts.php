<?php

namespace App\Listeners;

use App\Events\OrderCarted;
use App\Events\OrderCreated;
use App\Models\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ClearCarts
{

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderCarted  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        Cart::whereIn('cart_id' , $event->carts->pluck('id') )->delete() ;
    }
}
