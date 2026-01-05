<?php

namespace App\Repositories\Dashboard ;

use App\Events\OrderCreated;
use App\Interfaces\OrderRepositoryInterface;
use App\Models\Admin;
use App\Models\Order;
use App\Notifications\OrderCreatedNotification;
use App\Services\Orders\OrderServices;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
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

    public function create($userId)
    {
        DB::beginTransaction() ;
        try{
            $carts = $this->cartRepository->getCarts($userId);
            if ($carts->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            $subTotal = $this->orderService->subTotalOrder($carts) ;
            $total = $this->orderService->totalOrder($subTotal);
            $order = Order::create([
                'user_id' =>  $userId,
                'type' => 'online' ,
                'subtotal' => $subTotal ,
                'tax' => config('order.tax') * $subTotal ,
                'delivery_fee' => config('order.delivery_fee') ,
                'total' => $total
            ]);

            $this->orderItemRepository->create($order , $carts);
            $this->cartRepository->deleteAll($carts) ;

            event(new OrderCreated($order)) ; // send Notify using Pusher and store in DB
            DB::commit() ;

            return $order ;
        }
        catch(\Exception $e)
        {
            DB::rollBack() ;
            throw  $e ;
        }
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
        $order->update([
            'payment_status' => 'paid' ,
            'status' => 'confirmed'
        ]) ;
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




