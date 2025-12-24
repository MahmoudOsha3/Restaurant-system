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
        $total = $subTotal + ($subTotal * $taxRate);
        return $total ;
    }

}



