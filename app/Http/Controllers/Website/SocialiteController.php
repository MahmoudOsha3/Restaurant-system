<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\{User , Cart } ;
use Illuminate\Support\Facades\Auth;

class SocialiteController extends Controller
{
    public function login($provider)
    {
        $allowedProviders = ['google', 'github'];
        abort_unless(in_array($provider, $allowedProviders), 404);
        return Socialite::driver($provider)->redirect();
    }

    public function redirect($provider)
    {
        $allowedProviders = ['google', 'github'];
        abort_unless(in_array($provider, $allowedProviders), 404);
        $socialiteUser = Socialite::driver($provider)->user() ;

        $user = User::where('email', $socialiteUser->getEmail())->first(); // عدم الايميل

        if(! $user){
            $user = User::create([
                'name' => $socialiteUser->getName() ,
                'email' => $socialiteUser->getEmail() ,
                'provider_id' => $socialiteUser->getId() ,
                'provider_type' => $provider
            ]) ;
        }

        $user->update([
            'provider_id' => $socialiteUser->getId() ,
            'provider_type' => $provider
        ]) ;

        Auth::guard('web')->login($user,true);
        Cart::where('cookie_id', Cart::getCookieId())
            ->update([
                'user_id' => auth()->id(),
                'cookie_id' => null
            ]);
        return redirect()->route('home');
    }

}
