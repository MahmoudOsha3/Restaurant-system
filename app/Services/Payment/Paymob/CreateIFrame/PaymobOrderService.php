<?php

namespace App\Services\Payment\Paymob\CreateIFrame ;

use Illuminate\Support\Facades\Http;

class PaymobOrderService
{

    public static function generateOrder($token , $order_id , $amount)
    {
        $order = Http::withHeaders(['content-type' => 'application/json'])->post('https://accept.paymob.com/api/ecommerce/orders',
            [
                "auth_token" => $token ,
                "delivery_needed" => "false",
                "merchant_order_id" => $order_id ,
                "amount_cents" => $amount * 100,
                "items" => []
            ]);

        $order = $order->json();
        return $order ;
    }
}
