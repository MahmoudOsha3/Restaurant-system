<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Repositories\Api\CategoriesRepository;


class MenuController extends Controller
{
    public $categoriesRepo ;
    public function __construct(CategoriesRepository $categoriesRepo)
    {
        $this->categoriesRepo = $categoriesRepo ;
    }
    public function index()
    {
        $categories = $this->categoriesRepo->getCategories() ;
        return view('pages.website.menu.index' , compact('categories'));
    }
}
