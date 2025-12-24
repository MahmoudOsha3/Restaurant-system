<?php

namespace App\Interfaces ;

use App\Models\Cart;

interface CartRepositoryInterface
{
    public function getCarts();

    public function create($request);

    public function update($request , $cart) ;

    public function delete(Cart $cart) ;

    public function deleteAll($carts) ;

}
