<?php

namespace Tests\Feature\Website;

use App\Listeners\SendOrderCreatedNotification;
use App\Models\Admin;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Meal;
use App\Models\Role;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase , WithFaker ;

    public function test_user_can_create_order_using_carts()
    {
        Notification::fake();
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $meal = Meal::factory()->create(['category_id' => $category->id]);

        $cart = Cart::factory()->create(['meal_id' => $meal->id , 'user_id' => $user->id]);

        $order = $this->actingAs($user)->post(route('order.store'))
            ->assertStatus(302) ;

        $this->assertDatabaseHas('orders', [
            'user_id'=> $user->id ,
            'payment_status' => 'unpaid'
        ]);

        // $role = Role::factory()->create(['name'=> 'admin']) ;
        // $admin = Admin::factory()->create(['role_id' => $role->id ]) ;

        // Notification::assertSentTo($admin , SendOrderCreatedNotification::class , function ($notification) use ($admin){
        //     $notification->to($admin->id)->assertSent() ;
        // }) ;
    }
}

