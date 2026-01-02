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
        $status = Password::broker('admins')->sendResetLink($request->only('email')) ;

        if($status == Password::RESET_LINK_SENT){
            return redirect()->back()->with('status', $status) ;
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)] ,
        ]);
    }
}
