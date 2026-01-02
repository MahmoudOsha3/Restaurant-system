<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Repositories\Dashboard\CategoriesRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $categoryRepository ;

    public function __construct(CategoriesRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function home()
    {
        $meals = Meal::where('status' , 'active')->take(10)->get() ;
        return view('pages.website.home' , compact('meals')) ;
    }
}
