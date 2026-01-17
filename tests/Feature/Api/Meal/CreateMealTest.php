<?php

namespace Tests\Feature\Api\Meal;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateMealTest extends TestCase
{
    use RefreshDatabase , WithFaker ;

    public function test_unauthenticated_can_not_create_meal() : void
    {
        $response = $this->postJson(route('meal.store'), [
            'title'=> 'Meal 1' ,
        ])->assertStatus(401);
        $this->assertDatabaseEmpty('meals');
    }

    public function test_unauthorize_admin_can_not_create_meal() : void
    {
        $this->authenticatedAsAdmin('admin') ;
        $response = $this->postJson(route('meal.store'), [
            'title'=> 'Meal 1' ,
        ])->assertStatus(403);
        $response->assertJsonStructure(['message']) ;
    }

    public function test_authorize_admin_can_not_create_meal_with_invalidate_data_the_data_is_required() : void
    {
        $this->authenticatedAsAdmin('admin' , ['meal.create']) ; // authorize
        $response = $this->postJson(route('meal.store'),
                ['title' , 'price' ,'description' , 'image' , 'category_id']);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title' , 'price' ,'description' , 'image' , 'category_id']) ;
    }

    public function test_authorize_admin_can_not_create_meal_with_invalidate_data_price_must_numeric() : void
    {
        $this->authenticatedAsAdmin('admin' , ['meal.create']) ;
        $response = $this->postJson(route('meal.store'), ['price' => '12--EGP--']);
        $response->assertStatus(422)->assertJsonValidationErrors(['price']) ;
    }

    public function test_authorize_admin_can_not_create_meal_with_category_id_not_exists_in_categories_table() : void
    {
        $this->authenticatedAsAdmin('admin' , ['meal.create']);
        $category = $this->createCategory() ;
        $response = $this->postJson(route('meal.store'), ['title' => 'Child Cat' , 'category_id' => 7 ]) ;
        $response->assertStatus(422)->assertJsonValidationErrors(['category_id']) ;
    }

    public function test_authorize_admin_can_not_create_meal_without_image_file() : void
    {

        $this->authenticatedAsAdmin('admin' , ['meal.create']) ;
        $category = $this->createCategory() ;
        $response = $this->postJson(route('meal.store'), [
            'title' => 'Meal 1' ,
            'description' => 'Meal 1 Meal 1 Meal 1',
            'price' => 120  ,
            'compare_price' => 150 ,
            'image' => 'sadcso' , // not file
            'preparation_time' => 15 ,
            'status' => 'active' ,
            'category_id' => $category->id
        ])->assertStatus(422)->assertJsonValidationErrors('image');
        $this->assertDatabaseMissing('meals' , ['title' => 'Meal 1' ]) ;
    }

    public function test_authorize_admin_can_create_meal_with_validate_data() : void
    {
        Storage::fake('public') ;
        $file = UploadedFile::fake()->image('meal.jpg');

        $this->authenticatedAsAdmin('admin' , ['meal.create']) ;
        $category = $this->createCategory() ;
        $response = $this->postJson(route('meal.store'), [
            'title' => 'Meal 1' ,
            'description' => 'Meal 1 Meal 1 Meal 1',
            'price' => 120  ,
            'compare_price' => 150 ,
            'image' => $file ,
            'preparation_time' => 15 ,
            'status' => 'active' ,
            'category_id' => $category->id
        ])->assertCreated() ;
        $response->assertJsonStructure(['data' , 'msg']) ;
        $this->assertDatabaseHas('meals' , ['title' => 'Meal 1' ]) ;
    }

}
