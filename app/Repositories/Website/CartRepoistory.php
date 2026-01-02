<?php

namespace App\Repositories\Website ;

use App\Models\Cart;


class CartRepository
{
    public function getCarts()
    {
        $userId   = auth()->user()->id ;
        $cookieId = Cart::getCookieId();

        $carts = Cart::with('meal:id,title,price,image')
            ->where(function ($q) use ($userId, $cookieId) {
                if ($userId != null ) {
                    $q->where('user_id', $userId)
                        ->orWhere('cookie_id', $cookieId);
                } else {
                    $q->where('cookie_id', $cookieId);
                }
            })
            ->get();
        return $carts ;
    }

    public function create($data)
    {
        $cart = Cart::create($data) ; // id / cookieId => determine in model using observe
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




