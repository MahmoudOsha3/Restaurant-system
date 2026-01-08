<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CartRequest;
use App\Models\Cart;
use App\Repositories\Api\CartRepository;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ManageApiTrait ;
    protected $cartRepository ;
    public function __construct(CartRepository $cartRepository) {
        $this->cartRepository = $cartRepository;
    }

    public function index(Request $request)
    {
        $carts = $this->cartRepository->getCarts($request) ;
        return $this->successApi($carts , 'Carts fetched successfully') ;
    }

    public function store(CartRequest $request)
    {
        $cart = $this->cartRepository->create($request->validated()) ;
        return $this->createApi($cart , 'Cart created successfully') ;
    }


    public function show(Cart $cart)
    {
        return $this->successApi($cart , 'Cart fetched successfully') ;
    }

    public function update(CartRequest $request, Cart $cart)
    {
        $cart = $this->cartRepository->update($request , $cart) ;
        return $this->successApi($cart , 'Cart updated successfully') ;
    }


    public function destroy(Cart $cart)
    {
        $this->cartRepository->delete($cart) ;
        return $this->successApi(null , 'Cart deleted successfully') ;
    }
}
