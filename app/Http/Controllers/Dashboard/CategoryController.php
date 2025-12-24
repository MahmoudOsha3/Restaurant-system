<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ManageApiTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ManageApiTrait ;

    public function index()
    {
        try
        {
            // $this->authorize('viewAny' , Category::class );
            $categories = Category::withCount('meals')->latest()->get() ;
            return $this->successApi($categories , 'Categories fetched successfully') ;
        }
        catch(AuthorizationException $e)
        {
            return $this->faildApi('You are not authorized' , 403);
        }

    }

    public function store(Request $request)
    {
        $category = Category::create($request->all());
        return response()->json(['data' => $category] , 201) ;
    }

    public function show(Category $category)
    {
        return response()->json(['data' => $category] , 200) ;
    }

    public function update(Request $request, Category $category)
    {
        $category->update($request->all()) ;
        return response()->json(['data' => $category] , 200) ;
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['Msg' => 'Category is delete'] , 200) ;
    }
}
