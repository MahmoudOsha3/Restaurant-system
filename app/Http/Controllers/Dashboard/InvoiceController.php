<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\Dashboard\OrderRepository;
use App\Services\Invoice\InvoiceService;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use ManageApiTrait ;

    protected $orderRepository ;
    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }
    public function show($order_id)
    {
        $invoice = $this->orderRepository->getOrder($order_id) ;
        return $this->successApi($invoice , 'Invoice fetched successfully')  ;
    }
}
