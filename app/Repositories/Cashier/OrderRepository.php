<?php

namespace App\Repositories\Cashier ;

use App\Models\Cart;
use App\Models\Order;
use App\Repositories\Api\CartRepository;
use App\Repositories\Api\OrderItemRepository;
use App\Services\Orders\OrderServices;
use Exception;
use Illuminate\Support\Facades\DB;

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

    public function getHistoryOrdersTheDay($request)
    {
        $orders  = Order::cashier()->whereDate('created_at' , today())
            ->with(['orderItems:id,order_id,meal_title,price,quantity,total'])
            ->filter($request)->latest()->paginate(10);
        return $orders ;
    }

    public function create($adminId)
    {
        DB::beginTransaction() ;
        try{
            $carts = Cart::where('admin_id' , $adminId)->get() ;
            if ($carts->isEmpty()) {
                throw new Exception('Cart is empty');
            }

            $subTotal = $this->orderService->subTotalOrder($carts) ;
            $total = $this->orderService->totalOnSite($subTotal) ;
            $order = Order::create([
                'admin_id' =>  $adminId ,
                'type' => 'onsite' ,
                'subtotal' => $subTotal ,
                'tax' => config('order.tax') * $subTotal ,
                'delivery_fee' => 0 ,
                'total' => $total,
                'payment_status' => 'paid' ,
                'status' => 'completed'
            ]);
            $this->orderItemRepository->create($order , $carts);
            $this->cartRepository->deleteAll($carts) ;

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




