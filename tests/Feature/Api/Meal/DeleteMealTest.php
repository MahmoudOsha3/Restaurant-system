<?php

namespace Tests\Feature\Api\Meal;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteMealTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_can_not_delete_meal() : void
    {
        $category = $this->createCategory(['title' => 'Category 1']) ;
        $meal = $this->createMeal(['title' => 'Meal 1','category_id' => $category->id ]);
        $response = $this->deleteJson(route('meal.destroy' , $meal->id))->assertStatus(401);
        $this->assertDatabaseHas('meals', ['title'=> $meal->title]);
    }

    public function test_unauthorize_admin_can_not_delete_meal_exists_in_meals_table() : void
    {
        $this->authenticatedAsAdmin('admin' , []) ;
        $category = $this->createCategory();
        $meal = $this->createMeal(['title' => 'Meal 1','category_id' => $category->id ]);
        $this->deleteJson(route('meal.destroy' , $meal->id ))->assertStatus(403);
        $this->assertDatabaseHas('meals' , ['title' => $meal->title]);
    }

    public function test_authorize_admin_can_delete_meal_exists_in_categories_table() : void
    {
        $this->authenticatedAsAdmin('admin' , ['meal.delete']); // authorize
        $category = $this->createCategory() ;
        $meal = $this->createMeal(['title' => 'Meal 1' ,'category_id' => $category->id ]);
        $this->deleteJson(route('meal.destroy' , $meal->id ))->assertStatus(200);
        $this->assertDatabaseMissing('meals' , ['title' => $meal->title]);
    }

    public function test_authorize_admin_gets_404_when_delete_category_not_exists_in_categories_table() : void
    {
        $this->authenticatedAsAdmin('admin' , ['meal.delete']) ;
        $category = $this->createCategory() ;
        $meal = $this->createMeal(['category_id' => $category->id ]) ;
        $response = $this->deleteJson(route('meal.destroy', 20))
                        ->assertStatus(404);
    }


}
