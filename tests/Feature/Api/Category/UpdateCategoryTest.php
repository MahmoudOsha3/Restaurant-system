<?php

namespace Tests\Feature\Api\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_can_not_update_category() : void
    {
        $category = $this->createCategory(['title' => 'Category 1']) ;
        $response = $this->putJson(route('category.update' , $category->id), [
            'title'=> 'Category 2' ,
        ])->assertStatus(401);
        $this->assertDatabaseMissing('categories', ['title'=> 'Category 2']);
    }

    public function test_unauthorize_admin_can_not_update_category() : void
    {
        $this->authenticatedAsAdmin('admin');
        $category = $this->createCategory(['title' => 'Category 1']) ;
        $response = $this->putJson(route('category.update' , $category->id ), [
            'title'=> 'Category 2' ,
        ])->assertStatus(403)->assertJsonStructure(['message']) ;
        $this->assertDatabaseMissing('categories' , ['title' => 'Category 2' ]) ;
    }

    public function test_authorize_admin_can_not_update_category_with_invalidate_data_title_is_required() : void
    {
        $this->authenticatedAsAdmin('admin' , ['category.update']) ;
        $category = $this->createCategory(['title' => 'Category 1']) ;
        $response = $this->putJson(route('category.update' , $category->id ), []);
        $response->assertStatus(422)->assertJsonValidationErrors(['title']) ;
        $this->assertDatabaseMissing('categories', ['title'=> '']);
    }

    public function test_authorize_admin_can_not_update_category_with_invalid_data_title_must_string() : void
    {
        $this->authenticatedAsAdmin('admin' , ['category.update']) ;
        $category = $this->createCategory(['title' => 'Category 1']) ;
        $response = $this->putJson(route('category.update' , $category->id ), ['title' => 12 ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['title']) ;
        $this->assertDatabaseMissing('categories', ['title'=> '']);
    }

    public function test_authorize_admin_can_not_update_category_with_parent_id_not_exists_in_categories_table() : void
    {
        $this->authenticatedAsAdmin('admin' , ['category.update']) ;
        $category = $this->createCategory() ;
        $response = $this->putJson(route('category.update' , $category->id ), ['title' => 'Child Cat' , 'parent_id' => 7 ]) ;
        $response->assertStatus(422)->assertJsonValidationErrors(['parent_id']) ;
        $this->assertDatabaseMissing('categories', ['title' => 'Child Cat' , 'parent_id' => 7 ]);

    }

    public function test_admin_gets_404_when_update_category_not_exists_in_categories_table() : void
    {
        $this->authenticatedAsAdmin('admin' , ['category.update']) ;
        $response = $this->putJson(route('category.update' , 5 ), [
            'title'=> 'Category 2' ,
        ])->assertStatus(404);
        $this->assertDatabaseMissing('categories', ['title' => 'Category 2' ]);
    }

    public function test_authorize_admin_can_update_category_with_validate_data() : void
    {
        $this->authenticatedAsAdmin('admin' , ['category.update']) ;
        $category = $this->createCategory(['title' => 'Category 1']) ;
        $response = $this->putJson(route('category.update' , $category->id ), [
            'title'=> 'Category 2' ,
        ])->assertStatus(200)->assertJsonStructure(['data' , 'msg']) ;

        $this->assertDatabaseHas('categories' , ['title' => 'Category 2' ]) ;
    }
}
