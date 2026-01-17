<?php

namespace Tests\Feature\Api\Meal ;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchMealsTest extends TestCase
{
    use RefreshDatabase , WithFaker ;

    public function test_unauthenticated_can_not_fetch_meals_list() :void
    {
        $response = $this->getJson(route('meal.index'))
                ->assertStatus(401); // Unauthenticated
        $response->assertJsonStructure(['message']) ;
    }

    public function test_unauthenticated_can_not_fetch_single_meal() : void
    {
        $category = $this->createCategory() ;
        $meal = $this->createMeal(['category_id' => $category->id ]);
        $response = $this->getJson(route('meal.show' , $meal->id))
                    ->assertStatus(401)->assertJsonStructure(['message']);
    }

    public function test_unauthorize_admin_can_not_fetch_meals_list() : void
    {
        $this->authenticatedAsAdmin('admin') ; // unauthorize
        $response = $this->getJson(route('meal.index'))->assertStatus(403) ;
        $response->assertJsonStructure(['message']) ;
    }

    public function test_unauthorize_admin_can_not_fetch_single_category() : void
    {
        $this->authenticatedAsAdmin('admin') ; // unauthorize
        $category = $this->createCategory() ;
        $meal = $this->createMeal(['category_id' => $category->id ]);
        $this->getJson(route('meal.show' , $meal->id))
                        ->assertStatus(403);
    }

    public function test_authorize_admin_can_fetch_categories_list() : void
    {
        $this->authenticatedAsAdmin('admin' , ['meal.view']) ; // authorize
        $category = $this->createCategory() ;
        $meal = $this->createMeal(['category_id' => $category->id ]);
        $response = $this->getJson(route('meal.index'))->assertOk() ;
        $response->assertJsonStructure(['msg' , 'data']) ;
    }

    public function test_authorize_admin_can_fetch_single_category() : void
    {
        $this->authenticatedAsAdmin('admin' , ['meal.show']) ; // authorize
        $category = $this->createCategory() ;
        $meal = $this->createMeal(['category_id' => $category->id ]);
        $this->getJson(route('meal.show' , $meal->id))
                        ->assertOk() ;
    }

    public function test_authorize_admin_gets_404_when_meal_not_found() : void
    {
        $this->authenticatedAsAdmin('admin' , ['meal.show']) ;
        $category = $this->createCategory() ;
        $meal = $this->createMeal(['category_id' => $category->id ]);
        $this->getJson(route('meal.show' , 120 ))->assertStatus(404);
    }



}
