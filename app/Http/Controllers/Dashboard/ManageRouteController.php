<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Meal;
use Illuminate\Http\Request;

class ManageRouteController extends Controller
{
    public function dashboard()
    {
        $this->authorize("dashboardView", Admin::class) ;
        return view('pages.dashboard.home.index') ;
    }
    public function meals()
    {
        $this->authorize('viewAny' , Meal::class );
        return view('pages.dashboard.meal.index') ;
    }

    public function categories()
    {
        $this->authorize('viewAny' , Category::class );
        return view('pages.dashboard.categories.index') ;
    }

    public function roles()
    {
        $this->authorize("roleView", Admin::class);
        return view('pages.dashboard.rolesAndPermissions.index') ;
    }

    public function orders()
    {
        $this->authorize("ordersView", Admin::class);
        return view('pages.dashboard.orders.index') ;
    }

    public function admins()
    {
        $this->authorize("AdminsView", Admin::class);
        return view('pages.dashboard.admins.index') ;
    }

    public function invoices()
    {
        $this->authorize("InvoiceView", Admin::class);
        return view('pages.dashboard.invoices.index') ;
    }

    public function reports()
    {
        $this->authorize('reportsView', Admin::class) ;
        return view('pages.dashboard.reports.index');
    }

    public function users()
    {
        // $this->authorize('usersView', Admin::class) ;
        return view('pages.dashboard.users.index');
    }

}
