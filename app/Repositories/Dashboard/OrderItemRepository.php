<?php

namespace App\Repositories\Dashboard ;

use App\Interfaces\OrderItemRepositoryInterface;
use App\Models\OrderItem;

class OrderItemRepository implements OrderItemRepositoryInterface
{

    public function getOrders()
    {

    }

    public function create($order , $carts)
    {
        $items = [] ;
        foreach($carts as $key => $cart)
        {
            $items[] =  [
                'order_id' => $order->id ,
                'meal_id' => $cart->meal->id ,
                'meal_title' => $cart->meal->title ,
                'price' => $cart->meal->price ,
                'quantity' => $cart->quantity ,
                'total' => $cart->quantity * $cart->meal->price ,
                'created_at'=> now(),
                'updated_at'=> now(),
            ];
        }
        $OrderItem = OrderItem::insert($items);
        return $OrderItem ;
    }



    public function update($request , $order)
    {

    }

    public function delete(OrderItem $order)
    {
        $order->delete() ;
    }
}




