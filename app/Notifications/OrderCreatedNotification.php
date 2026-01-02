<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order ;
    public function __construct($order)
    {
        $this->order = $order ;
    }

    public function via($notifiable)
    {
        return ['database' , 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'title' => "New Order Created",
            'message' => "Order #{$this->order->id} has been created.",
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'order_id' => $this->order->id,
            'title' => "New Order Created",
            'message' => "Order #{$this->order->order_number} has been created by {$this->order->user->name}. ",
        ]) ;
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
