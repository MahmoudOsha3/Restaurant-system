<?php

namespace App\Payments\Gateways;

use App\Payments\Contracts\PaymentGatewayInterface;
use App\Services\Payment\Stripe\StripeServices;


class StripeGateway implements PaymentGatewayInterface
{
    public $stripeService ;

    public function __construct(StripeServices $stripeService ) {
        $this->stripeService = $stripeService;
    }

    public function pay($data)
    {
        $payment = $this->stripeService->createIframe($data);
        return $payment ;
    }

    public function verify($data) {

    }

    public function refund($data) {}
}
