<?php

namespace App\Services\Home ;

use App\Models\Order;

class ProfitService
{
    public function day()
    {
        $profit = Order::whereDate('created_at' , today() )->sum('total') ;
        return $profit ;
    }

    public function amonth()
    {
        $profit = Order::whereMonth('created_at' , now()->month )->sum('total') ;
        return $profit ;
    }

    public function year()
    {
        $profit = Order::whereYear('created_at' , now()->year )->sum('total') ;
        return $profit ;
    }


}

