<?php

namespace App\Interfaces ;

use App\Models\Cart;
use App\Models\Invoice;

interface InvoiceRepositoryInterface
{
    public function getInvoices();

    public function create($request);

    public function update($request , $invoice) ;

    public function delete(Invoice $invoice) ;

}
