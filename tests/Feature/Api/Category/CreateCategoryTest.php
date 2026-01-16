<?php

namespace Tests\Feature\Api\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateCategoryTest extends TestCase
{
use RefreshDatabase , WithFaker ;

    public function test_unauthenticated_can_not_create_category() : void
    {
        $response = $this->postJson(route('category.store'), [
            'title'=> 'Category 1' ,
        ])->assertStatus(401);
        $this->assertDatabaseEmpty('categories');
    }

    public function test_unauthorize_admin_can_not_create_category() : void
    {
        $this->authenticatedAsAdmin('admin') ;
        $response = $this->postJson(route('category.store'), [
            'title'=> 'Category 1' ,
        ])->assertStatus(403);
        $response->assertJsonStructure(['message']) ;
    }

    public function test_authorize_admin_can_not_create_category_with_invalid_data_title_is_required() : void
    {
        $this->authenticatedAsAdmin('admin' , ['category.create']) ; // authorize
        $response = $this->postJson(route('category.store'), ['title']);
        $response->assertStatus(422)->assertJsonValidationErrors(['title']) ;
    }

    public function test_authorize_admin_can_not_create_category_with_invalid_data_title_must_string() : void
    {
        $this->authenticatedAsAdmin('admin' , ['category.create']) ;
        $response = $this->postJson(route('category.store'), ['title' => 1212 ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['title']) ;
    }

    public function test_authorize_admin_can_not_create_category_with_parent_id_not_exists_in_categories_table() : void
    {
        $this->authenticatedAsAdmin('admin' , ['category.create']); // this role + permission
        $category = $this->createCategory() ;
        $response = $this->postJson(route('category.store'), ['title' => 'Child Cat' , 'parent_id' => 7 ]) ;
        $response->assertStatus(422)->assertJsonValidationErrors(['parent_id']) ;
    }


    public function test_authorize_admin_can_create_category_with_validate_data() : void
    {
        $this->authenticatedAsAdmin('admin' , ['category.create']) ;
        $response = $this->postJson(route('category.store'), [
            'title'=> 'Category 1' ,
        ])->assertCreated() ;
        $response->assertJsonStructure(['data' , 'msg']) ;
        $this->assertDatabaseHas('categories' , ['title' => 'Category 1' ]) ;
    }
    
}
