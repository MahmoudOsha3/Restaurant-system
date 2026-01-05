<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ManageApiTrait ;

    public function loginView()
    {
        return view('pages.login') ;
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|string|min:8',
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();

        if (!Hash::check($credentials['password'], $admin->password)) {
            return back()->withErrors(
                ['email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'])
                        ->withInput();
        }
        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();
        return to_route('dashboard') ;
    }


    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login') ;
    }
}
