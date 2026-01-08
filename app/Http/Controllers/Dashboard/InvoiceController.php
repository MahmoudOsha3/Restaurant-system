<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\InvoiceRequest;
use App\Models\Invoice;
use App\Models\Order;
use App\Repositories\Api\InvoiceRepository;
use App\Traits\ManageApiTrait;

class InvoiceController extends Controller
{
    use ManageApiTrait ;

    protected $invoiceRepository ;
    public function __construct(InvoiceRepository $invoiceRepository) {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function index()
    {
        $invoice = $this->invoiceRepository->getinvoices() ;
        return $this->successApi($invoice , 'Invoice fetched successfully')  ;
    }

    public function store(InvoiceRequest $request)
    {
        $invoice = $this->invoiceRepository->create($request->validated()) ;
        return $this->createApi($invoice , 'Invoice created successfully') ;
    }

    public function update(InvoiceRequest $request , Invoice $invoice )
    {
        $invoice = $this->invoiceRepository->update($request->validated() , $invoice) ;
        return $this->successApi($invoice , 'Invoice updated successfully') ;
    }

    public function destroy(Invoice $invoice)
    {
        $invoice = $this->invoiceRepository->delete($invoice);
        return $this->successApi(null , 'Invoice deleted successfully') ;
    }
}
