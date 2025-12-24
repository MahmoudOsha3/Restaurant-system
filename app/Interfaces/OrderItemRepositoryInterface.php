<?php

namespace App\Interfaces ;

use App\Models\OrderItem;

interface OrderItemRepositoryInterface
{
    public function getOrders();

    public function create($order , $carts);

    public function update($request , $order) ;

    public function delete(OrderItem $order) ;

}
