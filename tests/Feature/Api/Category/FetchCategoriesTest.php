<?php

namespace Tests\Feature\Api\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchCategoriesTest extends TestCase
{
    use RefreshDatabase , WithFaker ;

    public function test_unauthenticated_can_not_fetch_categories_list() :void
    {
        $response = $this->getJson(route('category.index'))
                ->assertStatus(401); // Unauthenticated
        $response->assertJsonStructure(['message']) ;
    }

    public function test_unauthenticated_can_not_fetch_single_category() : void
    {
        $category = $this->createCategory() ;

        $response = $this->getJson(route('category.show' , $category->id))
                    ->assertStatus(401)->assertJsonStructure(['message']);
    }

    public function test_unauthorize_admin_can_not_fetch_categories_list() : void
    {
        $this->authenticatedAsAdmin('admin') ; // unauthorize
        $response = $this->getJson(route('category.index'))->assertStatus(403) ;
        $response->assertJsonStructure(['message']) ;
    }

    public function test_unauthorize_admin_can_not_fetch_single_category() : void
    {
        $this->authenticatedAsAdmin('admin') ; // unauthorize
        $category = $this->createCategory() ;
        $this->getJson(route('category.show' , $category->id))
                        ->assertStatus(403);
    }

    public function test_authorize_admin_can_fetch_categories_list() : void
    {
        $this->authenticatedAsAdmin('admin' , ['category.view']) ; // authorize
        $response = $this->getJson(route('category.index'))->assertOk() ;
        $response->assertJsonStructure(['msg' , 'data']) ;
    }



    public function test_authorize_admin_can_fetch_single_category() : void
    {
        $this->authenticatedAsAdmin('admin' , ['category.show']) ; // authorize
        $category = $this->createCategory() ;
        $this->getJson(route('category.show' , $category->id))
                        ->assertOk() ;
    }

    public function test_admin_gets_404_when_category_not_found() : void
    {
        $this->authenticatedAsAdmin('admin' , ['category.show']) ;
        $category = $this->createCategory() ;
        $this->getJson('api/category/'. 2)->assertStatus(404);
    }

    public function test_category_returns_only_its_meals(): void
    {
        $this->authenticatedAsAdmin('admin' , ['category.show']);

        $category1 = $this->createCategory();
        $category2 = $this->createCategory();

        $meal1 = $this->createMeal(['category_id' => $category1 ]) ;
        $meal2 = $this->createMeal(['category_id' => $category2 ]) ;

        $response = $this->getJson(route('category.show', $category1));

        $response->assertJsonCount(1, 'data.meals'); // انا عندي اتنين رجع واحد يبقي كدة فل
    }

}
