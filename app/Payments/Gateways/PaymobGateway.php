<?php

namespace App\Payments\Gateways ;

use App\Payments\Contracts\PaymentGatewayInterface;
use App\Services\Payment\Paymob\PaymobService;

class PaymobGateway implements PaymentGatewayInterface
{
    protected $paymobServices ;
    public function __construct(PaymobService $paymobServices) {
        $this->paymobServices = $paymobServices;
    }

    public function pay($data)
    {
        return $this->paymobServices->createIframe($data);
    }

    public function verify($data)
    {
        return $this->paymobServices->verifyPaid($data);
    }

}
