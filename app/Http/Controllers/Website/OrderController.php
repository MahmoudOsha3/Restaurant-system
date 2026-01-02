<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\OrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Repositories\Dashboard\CartRepository;
use App\Repositories\Dashboard\OrderRepository;
use App\Services\Orders\OrderServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $orderRepoistory , $cartRepository , $orderServices ;
    public function __construct(OrderRepository $orderRepoistory , CartRepository $cartRepository , OrderServices $orderServices)
    {
        $this->orderRepoistory = $orderRepoistory ;
        $this->cartRepository = $cartRepository ;
        $this->orderServices = $orderServices ;


    }

    public function checkout()
    {
        $subTotal = 0;
        $carts = $this->cartRepository->getCarts(auth()->user()->id );
        $subTotal = $this->orderServices->subTotalOrder($carts) ;
        return view('pages.website.checkout' , compact('carts', 'subTotal'));
    }

    public function store(Request $request)
    {
        $order = $this->orderRepoistory->create(auth()->user()->id);
        return to_route('order.checkout') ;
    }


}
