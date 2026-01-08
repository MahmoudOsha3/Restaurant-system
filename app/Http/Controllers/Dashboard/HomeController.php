<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\Api\InvoiceRepository;
use App\Repositories\Api\OrderRepository;
use App\Repositories\Api\MealRepository;
use App\Services\Home\LatestOrdersServices;
use App\Services\Home\ManageDataService;
use App\Services\Home\ProfitService;
use App\Traits\ManageApiTrait;

class HomeController extends Controller
{
    use ManageApiTrait ;
    protected $orderRepository , $mealRepository , $profitService , $manageDataService , $latestOrders , $invoiceRepository ;

    public function __construct(
        OrderRepository $orderRepository ,
        MealRepository $mealRepository ,
        InvoiceRepository $invoiceRepository ,
        ProfitService $profitService ,
        ManageDataService $manageDataService ,
        LatestOrdersServices $latestOrders ) {

        $this->orderRepository = $orderRepository;
        $this->mealRepository = $mealRepository;
        $this->profitService = $profitService;
        $this->manageDataService = $manageDataService ;
        $this->latestOrders = $latestOrders ;
        $this->invoiceRepository = $invoiceRepository ;

    }
    public function index()
    {

        $data = $this->manageDataService->data(
                    $this->orderRepository->countOrders() ,
                    $this->mealRepository->countMeals() ,
                    $this->profitService->day() ,
                    $this->profitService->amonth() ,
                    $this->latestOrders->Orders() ,
                    $this->invoiceRepository->getExpensesOfMonth()
                ) ;

        return $this->successApi($data, 'Data fetched successfully');
    }
}
