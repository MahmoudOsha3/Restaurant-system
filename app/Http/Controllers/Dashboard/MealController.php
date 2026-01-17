<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\MealRequest;
use App\Models\Meal;
use App\Repositories\Api\MealRepository;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;


class MealController extends Controller
{
    protected $mealRepository ;
    use ManageApiTrait ;

    public function __construct(MealRepository $mealRepository)
    {
        $this->authorizeResource(Meal::class );
        $this->mealRepository = $mealRepository;
    }

    public function index(Request $request)
    {
        $meals = $this->mealRepository->getAll($request) ;
        return $this->successApi($meals , 'The data was successfully extracted') ;
    }

    public function store(MealRequest $request)
    {
        $meal = $this->mealRepository->create($request->validated() , $request['_token']) ;
        return $this->createApi($meal , 'Meal is created successfully') ;
    }


    public function show(Meal $meal)
    {
        return $this->successApi($meal , 'The data was successfully extracted');
    }

    public function update(MealRequest $request, Meal $meal)
    {
        $meal = $this->mealRepository->update($meal , $request->validated()) ;
        return $this->createApi($meal , 'Meal is updated successfully') ;
    }

    public function destroy(Meal $meal)
    {
        $this->mealRepository->delete($meal) ;
        return $this->successApi(null , 'Meal is deleted successfilly') ;
    }
}
