<?php

namespace Tests;

use App\Models\{Admin , Cart , Category , Meal , Role , User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication , RefreshDatabase ;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function createUser(array $attribute = [])
    {
        return User::factory()->create($attribute);
    }

    public function createAdmin(array $attribute = [])
    {
        return Admin::factory()->create($attribute);
    }

    public function createRole(array $attribute = [] , array $permissions = [])
    {
        return Role::factory()->withPermissions($permissions)->create($attribute);
    }

    public function createCart(array $attribute = [])
    {
        return Cart::factory()->create($attribute);
    }

    public function createMeal(array $attribute = [])
    {
        return Meal::factory()->create($attribute);
    }

    public function createCategory(array $attribute = [])
    {
        return Category::factory()->create($attribute);
    }

    public function authenticated()
    {
        $user = User::factory()->create();
        return $this->actingAs($user);
    }

    public function authenticatedAsAdmin($roleName , array $allowedPermissions = [])
    {
        $role = $this->createRole(['name' => $roleName] , $allowedPermissions);
        $admin = Admin::factory()->create(['role_id' => $role->id ]);
        return $this->actingAs($admin);
    }
}
