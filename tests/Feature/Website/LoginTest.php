<?php

namespace Tests\Feature\Website;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase , WithFaker ;

    public function test_user_not_login_with_invalid_email() :void
    {
        $response = $this->post(route('auth.check') , [
            'email'=> 'invaild-email',
            'password' => '123456789'
        ]) ;
        $response->assertSessionHasErrors(['email']);
    }

    public function test_user_not_login_with_not_exist_email_in_db() :void
    {
        $user = User::factory()->create([
            'email'=> 'mahmoud@gmail.com',
            'password' => Hash::make('123456789')
        ]);

        $response = $this->post(route('auth.check') , [
            'email' => 'ali@gmail.com' ,
            'password' => '123456789'
        ]);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();

    }

    public function test_user_not_login_with_invalid_password() :void
    {
        $user = User::factory()->create([
            'email'=> 'mahmoud@gmail.com',
            'password' => Hash::make('123456789')
        ]);

        $response = $this->post(route('auth.check') , [
            'email'=> $user->email ,
            'password'=> '12345fdjs6789'
        ]);
        $this->assertGuest();
    }

    public function test_email_and_password_are_required(): void
    {
        $response = $this->post(route('login'), []);
        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_user_is_rate_limited_after_many_failed_attempts(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->post(route('auth.check'), [
                'email' => 'test@gmail.com',
                'password' => '7o46454215ng',
            ]);
        }

        $this->post(route('auth.check'), [
            'email' => 'test@gmail.com',
            'password' => 'wrong01201955377',
        ])->assertStatus(429);
    }

    public function test_user_login_with_valid_credentails() : void
    {
        $user = User::factory()->create([
            'email' => 'mahmoud@gmail.com',
            'password' => Hash::make('123456789')
        ]);

        $response = $this->post(route('auth.check') , [
            'email' => $user->email ,
            'password' => '123456789'
        ]);
        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('auth.logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

}
