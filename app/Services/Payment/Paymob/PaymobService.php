<?php

namespace App\Services\Payment\Paymob ;

use App\Services\Payment\Paymob\CreateIFrame\PaymobKeyService ;
use App\Services\Payment\Paymob\Webhook\{PaymobCheckProccessService , PaymobVerifyHmacSerice};

class PaymobService
{
    protected $integrations_id , $paymobCheckProccess ; // setting in paymob بمعني اصح بتوفر دفع من خلال فيزا او محفظة او اي حاجة

    public function __construct(PaymobCheckProccessService $paymobCheckProccess) {
        $this->integrations_id = ['5369750' ,'5370393']; // config in paymob site, i can created in paymob site
        $this->paymobCheckProccess = $paymobCheckProccess ;
    }

    public function createIframe($data)
    {
        $paymentKey = PaymobKeyService::generatePaymentKey($data['order_id'] , $data['amount'] , $data['user'] , $this->integrations_id[0]) ;
        return redirect("https://accept.paymob.com/api/acceptance/iframes/" . '972298' . "?payment_token=" . $paymentKey['token']);
    }

    // webhook
    public function verifyPaid($request)
    {
        // $verify = PaymobVerifyHmacSerice::verifyHmac($request) ;
        // if (! $verify) {
        //     Log::error('Paymob HMAC verification failed', $request->all());
        //     abort(403, 'Invalid HMAC');
        // }
        return $this->paymobCheckProccess->checkSuccess($request) ;
    }
}
