<?php

namespace App\Repositories\Api ;

use App\Models\Category;

class CategoriesRepository
{

    public function getCategories()
    {
        $categories = Category::get() ;
        return $categories ;
    }

    public function getCategoriesWithMeals()
    {
        $categories = Category::with('meals')->get() ;
        return $categories ;
    }

    public function create($validated)
    {
        $cart = Category::create($validated) ;
        return $cart ;
    }

    public function update($request , $category)
    {
        //
    }

    public function delete(Category $category)
    {
        $category->delete() ;
    }

    public function deleteAll($categories)
    {
        Category::whereIn('id' , $categories->pluck('id'))->delete() ;
    }
}




