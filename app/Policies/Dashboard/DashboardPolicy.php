<?php

namespace App\Policies\Dashboard;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DashboardPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function dashboardView(Admin $admin)
    {
        return $admin->hasPermission('dashboard.view') ;
    }

    public function cashierView(Admin $admin)
    {
        return $admin->hasPermission('cashier.view') ;
    }

    public function cashierCreate(Admin $admin)
    {
        return $admin->hasPermission('cashier.create') ;
    }

    public function cashierHistory(Admin $admin)
    {
        return $admin->hasPermission('cashier.show') ;
    }

    public function roleView(Admin $admin)
    {
        return $admin->hasPermission('role.view');
    }

    public function InvoiceView(Admin $admin)
    {
        return $admin->hasPermission('invoice.view');
    }

    public function ordersView(Admin $admin)
    {
        return $admin->hasPermission('order.view');
    }

    public function AdminsView(Admin $admin)
    {
        return $admin->hasPermission('admin.view');
    }

    public function reportsView(Admin $admin)
    {
        return $admin->hasPermission('report.view') ;
    }

    public function usersView(Admin $admin)
    {
        return $admin->hasPermission('user.view') ;
    }
}
