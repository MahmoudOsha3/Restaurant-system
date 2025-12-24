<?php

namespace App\Interfaces ;

use App\Models\Meal;

interface MealRepositoryInterface
{
    public function getAll($request) ;

    public function create($request , $token);

    public function update(Meal $meal , $request ) ;

    public function delete(Meal $meal) ;


}
