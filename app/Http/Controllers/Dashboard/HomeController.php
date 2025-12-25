<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\Dashboard\OrderRepository;
use App\Repositories\MealRepository;
use App\Services\Home\ProfitService;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ManageApiTrait ;
    protected $orderRepository , $mealRepository , $profitService ;
    public function __construct(OrderRepository $orderRepository ,
        MealRepository $mealRepository , ProfitService $profitService ) {

        $this->orderRepository = $orderRepository;
        $this->mealRepository = $mealRepository;
        $this->profitService = $profitService;


    }
public function index()
{
    $data = [];
    $data['countOrders'] = $this->orderRepository->countOrders();
    $data['countMeals'] = $this->mealRepository->countMeals();
    $data['profitOfDay'] = $this->profitService->day();
    $data['profitOfMonth'] = $this->profitService->amonth();

    $data['recent_orders'] = Order::with('admin')
        ->latest()
        ->take(10)
        ->get()
        ->map(function($order) {
            return [
                'id'         => $order->id,
                'cashier'    => $order->admin->name ?? 'Admin',
                'created_at' => $order->created_at->toISOString(),
                'summary'    => 'طلب وجبات',
                'amount'     => number_format($order->total, 2),
            ];
        });

    $data['chart_data'] = [
        'profit_labels' => ['4pm', '5pm', '6pm', '7pm', '8pm', '9pm', '10pm'],
        'profit_values' => [1200, 1900, 1500, 2500, 3200, 2800, 3500],
    ];

    return $this->successApi($data, 'Data fetched successfully');
}
}
