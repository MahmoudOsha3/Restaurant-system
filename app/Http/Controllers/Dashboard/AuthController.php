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

    public function generateToken(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:admins,email' , 'password' => 'required|string|min:8']) ;
        $admin = Admin::where('email' , $request->email)->first() ;
        if(! Hash::check($request->password ,$admin->password))
        {
            return $this->faildApi('Invalid credentials' , 401 ) ;
        }
        $admin->tokens()->delete(); // if exists token before
        $token = $admin->createToken('admin-token')->plainTextToken ;
        $data = ['token' => $token ,'admin' => $admin] ;
        return $this->successApi($data , 'Authentication successfully') ;
    }
}
