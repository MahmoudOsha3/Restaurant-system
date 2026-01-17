<?php

namespace Tests\Feature\Api\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_can_not_delete_category() : void
    {
        $category = $this->createCategory(['title' => 'Category 1']) ;
        $response = $this->deleteJson(route('category.destroy' , $category->id))->assertStatus(401);
        $this->assertDatabaseHas('categories', ['title'=> 'Category 1']);
    }

    public function test_unauthorize_admin_can_not_delete_category_not_exists_in_categories_table() : void
    {
        $this->authenticatedAsAdmin('admin');
        $category = $this->createCategory(['title' => 'Category 1']) ;
        $this->deleteJson(route('category.destroy' , $category->id ))->assertStatus(403);
        $this->assertDatabaseHas('categories' , ['title' => $category->title]);
    }

    public function test_admin_can_delete_category_exists_in_categories_table() : void
    {
        $this->authenticatedAsAdmin('admin' , ['category.delete']); // authorize
        $category = $this->createCategory(['title' => 'Category 1']) ;
        $this->deleteJson(route('category.destroy' , $category->id ))->assertStatus(200);
        $this->assertDatabaseMissing('categories' , ['title' => $category->title]);
    }

    public function test_admin_gets_404_when_delete_category_not_exists_in_categories_table() : void
    {
        $this->authenticatedAsAdmin('admin' , ['category.delete']) ;
        $category = $this->createCategory(['title' => 'Category 1']) ;
        $response = $this->deleteJson(route('category.destroy', 20))
                        ->assertStatus(404);
    }


}
