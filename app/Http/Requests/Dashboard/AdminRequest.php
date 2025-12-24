<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true ;
    }


    public function rules()
    {
        return [
            'name' => 'required|string|max:255' ,
            'email' => 'required|unique:admins,email,' . $this->route('admin') ,
            'password' => 'required|string',
            'image' => 'nullable|image' ,
            'role_id' => 'required|exists:roles,id' ,
            'address' => 'required|string|max:255' ,
            // 'phone' => 'required|digits:11',
        ];
    }
}
