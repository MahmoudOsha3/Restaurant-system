<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\OrderRequest;
use App\Models\Order;
use App\Repositories\Dashboard\OrderRepository;
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
        $orders  = Order::whereDate('created_at' , today() )->with(['orderItems:id,order_id,meal_title,price,quantity,total' , 'admin:id,name'])
            ->filter($request)->latest()->paginate();
        $orders['status'] = ['dwmqo' , 'sam'] ;
        $orders = $this->orderRepository->getOrders($request);
        return $this->successApi($orders   , 'My Orders fetched successfully');
        // return Order::whereDate('created_at' , today() )->get() ;

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

    public function update(Request $request, Order $order)
    {
        //
    }

    public function destroy(Order $order)
    {
        $this->orderRepository->delete($order) ;
        return $this->successApi(null , 'Order deleted successfully') ;
    }
}


