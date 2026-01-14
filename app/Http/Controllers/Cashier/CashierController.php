<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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
        $this->authorize("cashierView" , Admin::class) ;
        $meals = $this->mealRepository->getAll($request) ;
        return view('pages.cashier.home.index' , compact('meals')) ;
    }

    public function createOrder()
    {
        $this->authorize("cashierCreate" , Admin::class) ;
        $order = $this->orderRepository->create(auth()->user()->id) ;
        return $this->successApi($order , 'Order Created successfully') ;
    }

    public function history()
    {
        $this->authorize("cashierHistory" , Admin::class) ;
        return view('pages.cashier.history.index');
    }

    public function getOrdersHistory(Request $request)
    {
        $this->authorize("cashierHistory" , Admin::class) ;
        $history = $this->orderRepository->getHistoryOrdersTheDay($request);
        return $this->successApi($history ,'History fetched successfully') ;
    }

}
