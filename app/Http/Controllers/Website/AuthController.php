<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Login
    public function login()
    {
        return view('pages.site.login') ;
    }
    public function checkLogin(Request $request)
    {
        $validated  = $request->validate([
            'email' => 'required|email|exists:users,email' ,
            'password' => 'required|string|min:8|max:20'
            ]) ;

        if(! Auth::attempt($validated))
        {
            return redirect()->back()->with('error' , 'البريد الالكتروني او كلمة المرور خطأ') ;
        }
        $request->session()->regenerate();
        return redirect()->route('home') ;
    }

    // Register
    public function register()
    {
        return view('pages.site.register') ;
    }

    public function createUser(RegisterUserRequest $request)
    {
        $validated = $request->validated() ;
        $validated['password'] = Hash::make($validated['password']) ;
        $user = User::create($validated);
        Auth::login($user) ;
        $request->session()->regenerate();
        return to_route('home') ;
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('home') ;
    }
}
