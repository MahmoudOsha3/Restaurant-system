<?php

namespace App\Services\Home;

use App\Models\Admin;
use App\Models\Order;
use App\Models\User;

class ManageDataService
{
public function data($countOrders, $countMeals, $profitOfDay, $profitOfMonth, $latest_orders, $expenses)
{
    $countUsers  = User::count();
    $countAdmins = Admin::count();

    $ordersStats = Order::selectRaw('
            SUM(CASE WHEN admin_id IS NULL THEN 1 ELSE 0 END) as online_orders,
            SUM(CASE WHEN admin_id IS NOT NULL THEN 1 ELSE 0 END) as cashier_orders
        ')
        ->first();

    return [
        'countOrders'       => $countOrders,
        'countMeals'        => $countMeals,
        'profitOfDay'       => $profitOfDay,
        'profitOfMonth'     => $profitOfMonth,
        'recent_orders'     => $latest_orders,
        'expensesOfMonth'   => $expenses,
        'countUsers'        => $countUsers,
        'countAdmins'       => $countAdmins,
        'onlineOrdersCount' => (int) $ordersStats->online_orders,
        'cashierOrders'     => (int) $ordersStats->cashier_orders,
        'chart_data'        => [
            'profit_labels' => ['4pm', '5pm', '6pm', '7pm', '8pm', '9pm', '10pm'],
            'profit_values' => [1200, 1900, 1500, 2500, 3200, 2800, 3500],
        ],
    ];
}


}

