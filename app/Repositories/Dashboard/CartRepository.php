<?php

namespace App\Repositories\Dashboard ;

use App\Interfaces\CartRepositoryInterface;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartRepository implements CartRepositoryInterface
{

    public function getCarts()
    {
        $carts = Cart::with('meal:id,title,price')->ForAdmin()->get() ;
        return $carts ;
    }

    public function create($data)
    {
        $data['admin_id'] = auth()->user()->id ;
        $cart = Cart::create($data) ;
        return $cart ;
    }

    public function update($request , $cart)
    {
        $cart->update(['quantity' => $request->quantity]) ;
        return $cart ;
    }

    public function delete(Cart $cart)
    {
        $cart->delete() ;
    }

    public function deleteAll($carts)
    {
        Cart::whereIn('id' , $carts->pluck('id'))->delete() ;
    }
}




