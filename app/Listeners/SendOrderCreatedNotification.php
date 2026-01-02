<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Admin;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendOrderCreatedNotification
{
    public function __construct()
    {
        //
    }

    public function handle(OrderCreated $event)
    {
        Notification::send(Admin::superAdmins()->get() ,
            new OrderCreatedNotification($event->order)
        );
    }
}
