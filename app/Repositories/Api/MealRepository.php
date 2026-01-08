<?php

namespace App\Repositories\Api ;

use App\Interfaces\MealRepositoryInterface;
use App\Models\Meal;
use App\Services\Meals\ImageServices;
use Exception;
use Illuminate\Support\Facades\Cache;

// deal with DB
class MealRepository implements MealRepositoryInterface
{
    protected $imageServices ;
    public function __construct(ImageServices $imageServices) {
        $this->imageServices = $imageServices;
    }

    public function getAll($request)
    {
        $meals = Meal::with('category:id,title')->filter($request)->latest()->paginate(15) ;
        return $meals ;
    }

    public function create($data , $token)
    {
        if (Cache::has('meal_' . $token )) {
            throw new Exception("Request duplicated" , 409 ) ;
        }
        Cache::put('meal_'. $token, true, 30) ;
        $data['image'] = $this->imageServices->uploade($data['image']) ; // override
        $meal = Meal::create($data);
        return $meal ;
    }

    public function update(Meal $meal , $data)
    {
        $data['image'] = $this->imageServices->update($meal->image , $data['image']) ;
        $meal->update($data) ;
        return $meal ;
    }

    public function delete(Meal $meal)
    {
        $this->imageServices->delete($meal->image) ;
        $meal->delete() ;
    }

    public function countMeals()
    {
        return Meal::count() ;
    }
}




