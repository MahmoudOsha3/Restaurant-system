<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true ;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:30|min:10' ,
            'email'=> 'required|email|unique:users,email' ,
            'password' => 'required|string|max:20|min:8|confirmed',
            'address' => 'required|string|min:10|max:255' ,
            'city' => 'required|string' ,
            'phone' => 'required|digits:11' ,
        ];
    }
}
