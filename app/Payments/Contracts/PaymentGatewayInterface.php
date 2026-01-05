<?php

namespace App\Payments\Contracts ;

interface PaymentGatewayInterface
{
    public function pay($data);

    public function verify($data) ;

}
