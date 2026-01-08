<?php

namespace App\Services\Home;


class ManageDataService
{
    public function data($countOrders , $countMeals , $profitOfDay ,$profitOfMonth , $latest_orders , $expenses)
    {
        $data = [
            'countOrders' => $countOrders ,
            'countMeals' => $countMeals ,
            'profitOfDay' => $profitOfDay ,
            'profitOfMonth' => $profitOfMonth ,
            'recent_orders' => $latest_orders ,
            'expensesOfMonth' => $expenses ,
            'chart_data' => [
                        'profit_labels' => ['4pm', '5pm', '6pm', '7pm', '8pm', '9pm', '10pm'],
                        'profit_values' => [1200, 1900, 1500, 2500, 3200, 2800, 3500],
                    ]
        ] ;
        return $data ;
    }

}

