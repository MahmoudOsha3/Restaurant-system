<?php

namespace App\Services\Payment\Stripe\createIframe ;

use Stripe\StripeClient;

class StripeSessionService
{
    public function create($lineItems , $order_id)
    {
        $stripe = new StripeClient(config('services.stripe.secret')) ;
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems ,
            'metadata' => [
                'order_id' => $order_id
            ],
            'mode' => 'payment',
            'success_url' => 'http://127.0.0.1:8000/user/orders',
        ]);
        return redirect($checkout_session->url);
    }
}
