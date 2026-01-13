<?php

namespace Tests;

use App\Models\Admin;
use App\Models\Cart;
use App\Models\Meal;
use App\Models\Role;
use App\Models\User;
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

    public function createRole(array $attribute = [])
    {
        return Role::factory()->create($attribute);
    }

    public function createCart(array $attribute = [])
    {
        return Cart::factory()->create($attribute);
    }

    public function createMeal(array $attribute = [])
    {
        return Meal::factory()->create($attribute);
    }

    public function authenticated()
    {
        $user = User::factory()->create();
        return $this->actingAs($user);
    }

    public function authenticatedAsAdmin($role_id = null)
    {
        $admin = Admin::factory()->create(['role_id' => $role_id ?? 1 ]);
        return $this->actingAs($admin);
    }
}
