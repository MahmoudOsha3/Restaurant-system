<?php

namespace App\Services\Payment\Stripe ;

use App\Services\Payment\Stripe\createIframe\StripeOrderProccessService;
use App\Services\Payment\Stripe\createIframe\StripeSessionService;

class StripeServices
{
    public $session , $orderProccess ;

    public function __construct(StripeSessionService $session , StripeOrderProccessService $orderProccess )
    {
        $this->session = $session ;
        $this->orderProccess = $orderProccess ;

    }

    public function createIframe($data)
    {
        $orderInStripe = $this->orderProccess->prepare($data) ; // line_items
        $iframe = $this->session->create($orderInStripe , $data['order_id'] );
        return $iframe ;
    }
}
