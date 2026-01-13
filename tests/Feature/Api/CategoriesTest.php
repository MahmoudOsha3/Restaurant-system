<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase , WithFaker ;

    // rather than user is admin but for only testing
    public function test_admin_can_fetch_categories() : void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('api/category')->assertOk() ;
        $response->assertJsonStructure(['msg' , 'data']) ;
    }

    public function test_admin_can_create_category() : void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('api/category', [
            'title'=> $this->faker->sentence ,
        ])->assertCreated() ;
    }

    public function test_admin_can_update_category() : void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create() ;
        $response = $this->actingAs($user)->put('api/category/'. $category->id , [
            'title' => $this->faker->sentence ,
        ])->assertOk() ;
    }

    public function test_admin_can_not_update_category_with_no_exist_in_database() : void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create() ;
        $response = $this->actingAs($user)->put('api/category/5', [
            'title' => $this->faker->sentence ,
        ])->assertNotFound() ;
    }

    public function test_admin_can_delete_category() : void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create() ;
        $this->actingAs($user)->delete('api/category/'. $category->id)->assertOk() ;
    }

    public function test_admin_can_not_delete_category_with_no_exist_in_database() : void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->delete('api/category/5')->assertNotFound() ;
    }

}

