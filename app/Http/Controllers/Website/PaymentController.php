<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Payments\Gateways\PaymobGateway;
use App\Payments\PaymentManager;
use App\Repositories\Dashboard\OrderRepository;
use App\Services\Payment\Paymob\PaymobVerifyHmacSerice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $payment , $orderRepository ;

    public function __construct(PaymentManager $payment , OrderRepository $orderRepository) {
        $this->payment = $payment ;
        $this->orderRepository = $orderRepository ;

    }

    public function pay(Request $request , $order_id)
    {
        $order = $this->orderRepository->getOrder($order_id);
        $gateway = PaymentManager::make('paymob') ;
        $payment = $gateway->pay(['amount' => $order->total ,'order_id' => $order->id , 'user' => auth()->user() ]) ;
        return $payment ;
    }

    public function webhook(Request $request)
    {
        // here code (in callback function)
    }


    // Only Test because XAMPP not support SSL to excute it in webhook
    public function callback(Request $request)
    {
        $gateway = PaymentManager::make('paymob') ;
        $payment = $gateway->verify($request) ; // data => [success , order , msg]
        if(! $payment['success']){
            return to_route('order.payment.failed') ;
        }
        return to_route('order.payment.success' , $payment['order_number']) ;
    }

    public function success($order_number)
    {
        return view('pages.website.payment.success' , compact('order_number')) ;
    }

    public function failed()
    {
        return view('pages.website.payment.failed') ;
    }
}
