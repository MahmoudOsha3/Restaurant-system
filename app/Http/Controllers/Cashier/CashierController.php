<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CartRequest;
use App\Models\Cart;
use App\Repositories\Api\CartRepository;
use App\Repositories\Api\MealRepository;
use App\Repositories\Cashier\OrderRepository;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    use ManageApiTrait ;
    protected $cartRepository , $orderRepository , $mealRepository ;
    public function __construct(OrderRepository $orderRepository , CartRepository $cartRepository , MealRepository $mealRepository )
    {
        $this->cartRepository = $cartRepository ;
        $this->orderRepository = $orderRepository ;
        $this->mealRepository = $mealRepository ;

    }

    public function index(Request $request)
    {
        $carts = $this->cartRepository->getCarts();
        $meals = $this->mealRepository->getAll($request) ;
        return view('pages.cashier.home.index' , compact('carts' , 'meals')) ;
    }

    public function createOrder()
    {
        $order = $this->orderRepository->create(auth()->user()->id) ;
        return $this->successApi($order , 'Order Created successfully') ;
    }

    public function history(Request $request)
    {
        $history = $this->orderRepository->getHistoryOrdersTheDay($request);
        return $this->successApi($history ,'History fetched successfully') ;
    }

}
