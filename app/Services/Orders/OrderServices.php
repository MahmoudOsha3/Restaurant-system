<?php

namespace App\Services\Orders ;

use App\Models\Meal;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderServices
{
    public function subTotalOrder($carts)
    {
        $subTotal = 0;
        foreach ($carts as $cart) {
            if ($cart->meal) {
                $subTotal += $cart->meal->price * $cart->quantity;
            }
        }
        return $subTotal;
    }

    public function totalOrder($subTotal)
    {
        $taxRate = config('order.tax'); // 0.14
        $total = $subTotal + ($subTotal * $taxRate) + config('order.delivery_fee') ;
        return $total ;
    }

    public function totalOnSite($subTotal)
    {
        $taxRate = config('order.tax') ;
        $total = $subTotal + ($subTotal * $taxRate) ;
        return $total ;
    }

    public static function updateForPayment($order_id)
    {
        Order::where('id', $order_id)->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
            ]);
        return true ;
    }

}



