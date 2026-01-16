<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CategoryRequest;
use App\Models\Category;
use App\Traits\ManageApiTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ManageApiTrait ;

    public function __construct() {
        $this->authorizeResource(Category::class) ;
    }

    // it can use CategoryRepo without controller but for only testing
    public function index()
    {
        try
        {
            $categories = Category::withCount('meals')->latest()->get() ;
            return $this->successApi($categories , 'Categories fetched successfully') ;
        }
        catch(AuthorizationException $e)
        {
            return $this->faildApi('You are not authorized' , 403);
        }

    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated()) ;
        return $this->createApi($category ,'Category created successfully');
    }

    public function show(Category $category)
    {
        $category = $category->load('meals');
        return response()->json(['data' => $category] , 200) ;
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category = $category->update($request->validated()) ;
        return $this->successApi($category , 'Category updated successfully') ;
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return $this->successApi(null , 'Category deleted successfully') ;
    }

}
