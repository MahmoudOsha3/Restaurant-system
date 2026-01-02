<?php

namespace App\Interfaces ;

use App\Models\Order;

interface OrderRepositoryInterface
{
    public function getOrders($request) ;

    public function create($request);

    public function getOrder($id) ;

    public function update($request , $cart) ;

    public function delete(Order $cart) ;

    public function countOrders();

}
