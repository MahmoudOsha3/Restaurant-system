<?php

namespace Tests\Feature\Api\Meal ;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateMealTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_can_not_update_meal() : void
    {
        $category = $this->createCategory() ;
        $meal = $this->createMeal(['title' => 'Meal 1','category_id' => $category->id ]);
        $response = $this->putJson(route('meal.update' , $meal->id), [
            'title'=> 'Meal 2' ,
        ])->assertStatus(401);
        $this->assertDatabaseMissing('meals', ['title'=> 'Meal 2']);
    }

    public function test_unauthorize_admin_can_not_update_meal() : void
    {
        $this->authenticatedAsAdmin('admin' , []);
        $category = $this->createCategory() ;
        $meal = $this->createMeal(['title' => 'Meal 1','category_id' => $category->id ]);
        $response = $this->putJson(route('meal.update' , $meal->id), [
            'title'=> 'Meal 2' ,
        ])->assertStatus(403)->assertJsonStructure(['message']) ;
        $this->assertDatabaseMissing('categories' , ['title' => 'Meal 2']) ;
    }

    public function test_authorize_admin_can_not_update_meal_with_invalidate_data_data_is_required() : void
    {
        $this->authenticatedAsAdmin('admin' , ['meal.update']) ; // authorize
        $category = $this->createCategory() ;
        $meal = $this->createMeal(['title' => 'Meal 1','category_id' => $category->id ]);
        $response = $this->putJson(route('meal.update', $meal->id),
                ['title' , 'price' ,'description' , 'image' , 'category_id']);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title' , 'price' ,'description' , 'image' , 'category_id']) ;
    }

    public function test_authorize_admin_can_not_update_meal_with_invalidate_data_price_must_numeric() : void
    {
        $this->authenticatedAsAdmin('admin' , ['meal.update']) ; // authorize
        $category = $this->createCategory() ;
        $meal = $this->createMeal(['category_id' => $category->id ]);
        $response = $this->putJson(route('meal.update', $meal->id),
                ['price' => '12k']) ;
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['price']) ;
    }

    public function test_admin_gets_404_when_update_meal_not_exists_in_meals_table() : void
    {
        $this->authenticatedAsAdmin('admin' , ['meal.update']) ;
        $response = $this->putJson(route('meal.update' , 5 ), [
            'title'=> 'Meal 123' ,
        ])->assertStatus(404);
    }

    public function test_authorize_admin_can_update_meal_with_validate_data() : void
    {

        $this->authenticatedAsAdmin('admin' , ['meal.update']) ; // authorize
        $category = $this->createCategory() ;
        $meal = $this->createMeal(['title' => 'Meal 1','category_id' => $category->id ]);
        Storage::fake('public') ;
        $file = UploadedFile::fake()->image('index.jpg') ;
        $response = $this->putJson(route('meal.update', $meal->id), [
            'title' => 'Meal 2' ,
            'description' => 'Meal 2 Meal 1 Meal 1',
            'price' => 120  ,
            'compare_price' => 150 ,
            'image' => $file ,
            'preparation_time' => 15 ,
            'status' => 'active' ,
            'category_id' => $category->id
        ]) ;
        $response->assertStatus(201) ;
        $this->assertDatabaseHas('meals' , ['title' => 'Meal 2']) ;
        $this->assertDatabaseMissing('meals', ['title'=> 'Meal 1']) ;
    }
}
