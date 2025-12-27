<?php

namespace App\Services\Home ;

use App\Models\Order;

class LatestOrdersServices
{
    public function Orders()
    {
        $orders = Order::with('admin')->latest()->take(10)->get()
            ->map(function($order) {
                return [
                        'order_number' => $order->order_number,
                        'cashier'    => $order->admin->name ?? 'Admin',
                        'created_at' => $order->created_at->toISOString(),
                        'items'    => $order->orderItems ,
                        'amount'     => number_format($order->total, 2),
                    ];
                });
        return $orders ;
    }



}

