<?php

namespace App\Services\Payment\Stripe\createIframe ;

use App\Repositories\Api\OrderRepository;

class StripeOrderProccessService
{
    public $orderRepository ;
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository ;
    }
    public function prepare($data)
    {
        $order = $this->orderRepository->getOrder($data['order_id']) ;

        if (!$order || $order->orderItems->isEmpty()) {
            abort(404);
        }

        if ($order->payment_status === 'paid') {
            abort(400, 'Order already paid');
        }

        $lineItems = [] ;
        foreach($order->orderItems as $item)
        {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'egp',
                    'product_data' => [
                        'name' => $item->meal_title ,
                    ],

                    'unit_amount' => $item->price * 100 ,
                ],
                'quantity' => $item->quantity ,
            ] ;
        }

        // Fees
        $serviceFee = $order->tax + $order->delivery_fee ;
        $lineItems[] = [
            'price_data' => [
                'currency' => 'egp',
                'product_data' => [
                    'name' => 'Service Fee',
                ],
                'unit_amount' => $serviceFee * 100,
            ],
            'quantity' => 1,
        ];
        return $lineItems ;
    }
}
