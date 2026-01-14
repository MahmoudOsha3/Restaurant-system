<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\CartRequest;
use App\Models\Cart;
use App\Repositories\Api\CartRepository;
use App\Services\Orders\OrderServices;
use App\Traits\ManageApiTrait;

class CartController extends Controller
{
    use ManageApiTrait ;
    protected $cartRepository , $orderServices ;

    public function __construct(CartRepository $cartRepository , OrderServices $orderServices) {
        $this->cartRepository = $cartRepository;
        $this->orderServices = $orderServices ;
    }

    public function index()
    {
        $userId = auth()->check() ? auth()->id() : null;
        $carts = $this->cartRepository->getCarts($userId);
        $subTotal = $this->orderServices->subTotalOrder($carts);
        return view('pages.website.carts.index', compact('carts', 'subTotal'));
    }

    public function getCarts()
    {
        $carts = $this->cartRepository->getCarts(auth()->user()->id ?? null ) ;
        return $this->successApi($carts , 'Data fetched successfully') ;
    }

    public function store(CartRequest $request)
    {
        $cart = $this->cartRepository->create($request->validated()) ;
        return $this->successApi($cart , 'Data stored successfully') ;
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
