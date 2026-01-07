<?php

namespace App\Services\Home ;

use App\Models\Order;

class LatestOrdersServices
{
    public function Orders()
    {
        $orders = Order::with('user')->latest()->take(10)->get()
            ->map(function($order) {
                return [
                        'order_number' => $order->order_number,
                        'created_by'    => $order->user->name ?? 'Admin' ,
                        'created_at' => $order->created_at->toISOString(),
                        'items'    => $order->orderItems ,
                        'amount'     => number_format($order->total, 2),
                        'payment_status' => $order->payment_status ,
                    ];
                });
        return $orders ;
    }



}

