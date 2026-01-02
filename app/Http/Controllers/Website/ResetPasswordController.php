<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    public function index()
    {
        return view('pages.website.auth.reset') ;
    }

    public function reset(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']) ;

        // send email (check email in DB , create token for reset , store token in password_reset table )
        $status = Password::broker('users')->sendResetLink($request->only('email')) ;

        if($status == Password::RESET_LINK_SENT){
            return redirect()->back()->with('status', $status) ;
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)] ,
        ]);
    }

    public function showResetForm($token)
    {
        return view('pages.website.auth.UpdatePassword', ['token' => $token]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('auth.login')->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
