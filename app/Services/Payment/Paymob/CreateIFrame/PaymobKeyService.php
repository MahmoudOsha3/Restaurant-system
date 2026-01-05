<?php

namespace App\Services\Payment\Paymob\CreateIFrame ;

use Illuminate\Support\Facades\Http;

class PaymobKeyService
{
    public static function generatePaymentKey($order_id , $amount , $user , $integrations_id)
    {
        $token = PaymobTokenService::getCachedToken() ;
        if (!$token) {
            throw new \Exception("Failed to get Authentication Token from Paymob.");
        }
        $paymentOrder = PaymobOrderService::generateOrder($token , $order_id , $amount) ;

        $response = Http::post(config('services.paymob.base_url') . '/api/acceptance/payment_keys', [
            "auth_token" => $token,
            "amount_cents" => $amount * 100 ,
            "expiration" => 3600,
            "merchant_order_id" => $order_id ,
            "order_id" => $paymentOrder['id'] ,
            "billing_data" => [
                'first_name' => $user->name ,
                'last_name' => 'Customer' ,
                'phone_number' => $user->phone ,
                'address' => $user->address ,
                'street' => 'NA' ,
                'building' => 'NA' ,
                'floor' => 'NA' ,
                'apartment' => 'NA' ,
                'city' => $user->city  ,
                'country' => 'NA' ,
                'email' => $user->email ,
            ],
            "currency" => "EGP",
            "integration_id" => $integrations_id,  // مثلاً أول وسيلة دفع Visa
        ]);

        return $response->json();
    }
}
