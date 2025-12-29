<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true ;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:30|min:10',
            'email' => 'required|email|unique:users,email,'. auth()->user()->id ,
            'address' => 'required|string|max:550' ,
            'city' => 'required|string' ,
            'password' => 'required|string|min:8|max:12' ,
            'phone' => 'required|digits:11'
        ];
    }
}
