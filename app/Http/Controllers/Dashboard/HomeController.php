<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\Api\OrderRepository;
use App\Repositories\Api\MealRepository;
use App\Services\Home\LatestOrdersServices;
use App\Services\Home\ManageDataService;
use App\Services\Home\ProfitService;
use App\Traits\ManageApiTrait;

class HomeController extends Controller
{
    use ManageApiTrait ;
    protected $orderRepository , $mealRepository , $profitService , $manageDataService , $latestOrders ;

    public function __construct(
        OrderRepository $orderRepository ,
        MealRepository $mealRepository ,
        ProfitService $profitService ,
        ManageDataService $manageDataService ,
        LatestOrdersServices $latestOrders ) {

        $this->orderRepository = $orderRepository;
        $this->mealRepository = $mealRepository;
        $this->profitService = $profitService;
        $this->manageDataService = $manageDataService ;
        $this->latestOrders = $latestOrders ;

    }
    public function index()
    {

        $data = $this->manageDataService->data(
                    $this->orderRepository->countOrders() ,
                    $this->mealRepository->countMeals() ,
                    $this->profitService->day() ,
                    $this->profitService->amonth() ,
                    $this->latestOrders->Orders()
                ) ;

        return $this->successApi($data, 'Data fetched successfully');
    }
}
