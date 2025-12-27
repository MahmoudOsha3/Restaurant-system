<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManageRouteController extends Controller
{
    public function dashboard()
    {
        return view('pages.dashboard.home.index') ;
    }
    public function meals()
    {
        return view('pages.dashboard.meal.index') ;
    }

    public function categories()
    {
        return view('pages.dashboard.categories.index') ;
    }

    public function roles()
    {
        return view('pages.dashboard.rolesAndPermissions.index') ;
    }

    public function orders()
    {
        return view('pages.dashboard.orders.index') ;
    }

    public function admins()
    {
        return view('pages.dashboard.admins.index') ;
    }

    public function invoices()
    {
        return view('pages.dashboard.invoices.index') ;
    }






}
