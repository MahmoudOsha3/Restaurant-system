<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CartRequest;
use App\Models\Cart;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ManageApiTrait ;

    public function index()
    {
        $carts = Cart::with('meal:id,title,price')->where('admin_id' , auth()->user()->id)->get() ;
        return  $this->successApi($carts , 'carts fetched successfully !') ;
    }

    public function store(CartRequest $request)
    {
        $cart = Cart::cashier($request)->first() ;
        
        if ($cart) {
            $cart->increment('quantity');
        } else {
            $cart = Cart::create($request->validated());
        }
        $cart->load('meal');
        return $this->successApi($cart, 'تم تحديث السلة بنجاح');
    }


    public function destroy($id)
    {
        Cart::findorfail($id)->delete();
        return $this->successApi(null ,'Carts deleted successfully') ;
    }
}
