<?php

namespace App\Payments ;

use App\Payments\Gateways\PaymobGateway;
use App\Payments\Gateways\StripeGateway;
use InvalidArgumentException;

class PaymentManager
{
    public static function make(string $gateway)
    {
        return match ($gateway){
            'paymob' => app(PaymobGateway::class) ,
            'stripe' => app(StripeGateway::class) ,
            default => throw new InvalidArgumentException('Gateway not supported'),
        };
    }
}
