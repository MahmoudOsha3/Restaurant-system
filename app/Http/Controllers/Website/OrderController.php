<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\OrderRequest;
use App\Models\Order;
use App\Repositories\Api\CartRepository;
use App\Repositories\Api\OrderRepository;
use App\Services\Orders\OrderServices;


class OrderController extends Controller
{
    protected $orderRepoistory , $cartRepository , $orderServices ;
    public function __construct(OrderRepository $orderRepoistory , CartRepository $cartRepository , OrderServices $orderServices)
    {
        $this->orderRepoistory = $orderRepoistory ;
        $this->cartRepository = $cartRepository ;
        $this->orderServices = $orderServices ;
    }

    // all orders of my authentication
    public function orders()
    {
        $orders = Order::with('orderItems')->where('user_id' , auth()->user()->id )->latest()->get() ;
        return view('pages.website.order.myOrders' , compact('orders'));
    }

    public function store(OrderRequest $request)
    {
        $order = $this->orderRepoistory->create(auth()->user()->id);
        return to_route('orders.checkout') ;
    }


}
