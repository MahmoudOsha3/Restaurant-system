<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\Api\OrderRepository;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ManageApiTrait ;
    protected $orderRepository ;
    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        $orders = $this->orderRepository->getOrders($request);
        return $this->successApi($orders   , 'My Orders fetched successfully');
    }

    public function ordersDay()
    {
    }

    public function store(Request $request)
    {
        $order = $this->orderRepository->create($request) ;
        return $this->successApi($order  , 'Order created successfully') ;
    }

    public function show(Order $order)
    {
        $order = $this->orderRepository->getOrder($order) ;
        return $this->successApi($order  , 'Order created successfully') ;
    }

    // update status of order
    public function update(Request $request, Order $order)
    {
        $order = $this->orderRepository->cahengeStatus($request , $order);
        return $this->successApi($order , 'Order updated sucessfully');
    }

    public function destroy(Order $order)
    {
        $this->orderRepository->delete($order) ;
        return $this->successApi(null , 'Order deleted successfully') ;
    }
}


