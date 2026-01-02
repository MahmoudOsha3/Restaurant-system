<?php

namespace App\Repositories\Website ;

use App\Models\Order;
use App\Repositories\Dashboard\OrderItemRepository;
use App\Services\Orders\OrderServices;
use Illuminate\Support\Facades\DB ;

class OrderRepository
{
    protected $orderService , $cartRepository , $orderItemRepository ;

    public function __construct(OrderServices $orderService ,
        CartRepository $cartRepository ,
        OrderItemRepository $orderItemRepository){

        $this->orderService = $orderService;
        $this->cartRepository = $cartRepository ;
        $this->orderItemRepository = $orderItemRepository ;

    }

    public function getOrders($request)
    {
        $orders  = Order::with(['orderItems:id,order_id,meal_title,price,quantity,total' , 'admin:id,name'])
            ->filter($request)->latest()->paginate(10);
        return $orders ;
    }

    public function create($request)
    {
        // $
    }

    public function getOrder($order)
    {
        if(! is_object($order)) // $order here is order_id
        {
            $order = Order::with('orderItems:order_id,meal_title,price,quantity,total')->findorfail($order) ;
        }
        $order->load(['orderItems:order_id,meal_title,price,quantity,total']) ;
        return $order ;
    }

    public function update($request , $order)
    {

    }

    public function delete(Order $order)
    {
        $order->delete() ;
    }

    public function countOrders()
    {
        return Order::count() ;
    }
}




