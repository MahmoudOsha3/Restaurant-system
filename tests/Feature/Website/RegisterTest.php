<?php

namespace Tests\Feature\Website;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase , WithFaker ;

    public function test_an_guest_cannot_register_a_user_with_invalid_data()
    {
        $user = [
            'name' => 'mam',
            'email' => 'mahsakm55496',
            'email_verified_at' => now(),
            'password' => '123' ,
            'password_confirmation' => '123' ,
            'remember_token' => Str::random(10),
            'address' => $this->faker->streetAddress() ,
            'city' => $this->faker->city() ,
            'phone' => '0120195537' ,
        ] ;
        $response = $this->post(route('auth.store') , $user ) ;
        $response->assertStatus(302) ;
        $response->assertSessionHasErrors(['name' , 'email' , 'password'  , 'phone']) ;
    }

    public function test_an_guest_cannot_register_with_non_unique_email() :void
    {
        User::factory()->create(['email' => 'mahmoud.osha@gmail.com']) ;
        $response = $this->post(route('auth.store') , [
            'name' => 'Mahmoud Osha',
            'email' => 'mahmoud.osha@gmail.com',
            'email_verified_at' => now(),
            'password' => '123456789' ,
            'password_confirmation' => '123456789' ,
            'remember_token' => Str::random(10),
            'address' => $this->faker->streetAddress() ,
            'city' => $this->faker->city() ,
            'phone' => '01201955377' ,
        ] ) ;
        $response->assertSessionHasErrors(['email']) ;
    }

    public function test_guest_can_register_a_user_with_valid_data_and_become_authenticated():void
    {
        $user = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '123456789' ,
            'password_confirmation' => '123456789' ,
            'remember_token' => Str::random(10),
            'address' => $this->faker->streetAddress() ,
            'city' => $this->faker->city() ,
            'phone' => '01201955377' ,
        ] ;
        $response = $this->post(route('auth.store'), $user);
        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('users', ['name'=> $user['name'],'email'=> $user['email']]) ;
        $this->assertAuthenticated() ;
        $this->assertEquals($user['email'], auth()->user()->email) ;
    }

}
