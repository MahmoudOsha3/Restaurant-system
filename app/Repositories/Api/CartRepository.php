<?php

namespace App\Repositories\Api ;

use App\Interfaces\CartRepositoryInterface;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartRepository implements CartRepositoryInterface
{
    public function getCarts($user_id = null)
    {
        $query = Cart::with('meal:id,title,price,image');

        if ($user_id) {
            return $query->where('user_id', $user_id)->get();
        }
        return $query->where('cookie_id', Cart::getCookieId())->get();
    }


    public function create($data)
    {
        $cart = Cart::create($data) ; // id / cookieId => determine in model using observe
        return $cart ;
    }

    public function update($request , $cart)
    {
        $request->quantity == 1 ? $cart->increment('quantity',1) : $cart->decrement('quantity' , 1 ) ;
        if($cart->quantity <= 0 ){
            $cart->delete($cart) ;
        }
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




