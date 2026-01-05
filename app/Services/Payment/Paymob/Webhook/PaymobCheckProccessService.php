<?php

namespace App\Services\Payment\Paymob\Webhook ;

use App\Models\Order;
use App\Models\Payment;
use App\Repositories\Dashboard\OrderRepository;


class PaymobCheckProccessService
{
    public $orderRepository ;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository ;
    }

    public  function checkSuccess($data)
    {
        $merchantOrderId = $data['merchant_order_id'];
        if($data['success'] == 'true'){

            $order  = $this->orderRepository->getOrder($merchantOrderId) ; // find or fail
            $this->orderRepository->update($data , $order); // update (payment_status => 'paid')

            Payment::updateOrcreate([
                'order_id' => $merchantOrderId ,
                'transaction_number' => $data['order'] ,
            ],[
                'method' => 'card',
                'status' => 'paid' ,
                'amount' => $data['amount_cents'] / 100 ,
                ]) ;

            return ['success' => true , 'order_number' => $order->order_number ] ;
        }
        return ['success' => false , 'msg' => 'Payment Proccess is failed' ] ;

    }
}
