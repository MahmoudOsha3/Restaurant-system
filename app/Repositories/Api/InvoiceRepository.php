<?php

namespace App\Repositories\Api ;

use App\Interfaces\InvoiceRepositoryInterface;
use App\Models\Invoice;

class InvoiceRepository implements InvoiceRepositoryInterface
{

    public function getInvoices()
    {
        $invoices = Invoice::with('admin:id,name')->latest()->get();
        return $invoices ;
    }

    public function create($data)
    {
        $invoice = Invoice::create($data) ;
        return $invoice ;
    }

    public function update($data , $invoice)
    {
        $invoice = $invoice->update($data) ;
        return $invoice ;
    }

    public function delete(Invoice $invoice)
    {
        $invoice->delete() ;
    }
}




