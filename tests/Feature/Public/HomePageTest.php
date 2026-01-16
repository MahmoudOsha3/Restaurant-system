<?php

namespace Tests\Feature\Public;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase ;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_home_page_is_accssible()
    {
        $res = $this->get(route('home'))->assertStatus(200);
        $res->assertViewIs('pages.website.home.index') ;
        $res->assertSee('login');
    }

    public function test_unauthenticated_user_see_login_and_register_links():void
    {
        $response = $this->get(route('home'))->assertStatus(200);
        $response->assertSee('href="' . route('auth.login') .'"' , false);
    }

    public function test_authenticated_user_see_logout_link():void
    {
        $this->authenticated() ;
        $response = $this->get(route('home'))->assertStatus(200);
        $response->assertSee('form method="POST" action="'. route('auth.logout') .'"' ,false) ;
    }

    public function test_authenticated_user_see_his_name()
    {
        $this->authenticated() ;
        $response = $this->get(route('home'))->assertStatus(200);
        $response->assertSee(e(auth()->user()->name) , false) ;
    }

    public function test_authenticated_user_can_see_your_carts_for_create_order() :void
    {
        $user = $this->createUser() ;
        $response = $this->actingAs($user)->get(route('home'))->assertStatus(200) ;
        $response->assertSee('href="'. route('orders.checkout') .'"' , false);
    }
}
