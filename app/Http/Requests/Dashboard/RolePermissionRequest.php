<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class RolePermissionRequest extends FormRequest
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
            'role_id' => 'required|exists:role,id',
            'permission' => 'required|string|max:255',
            'authorize' => 'required|in:allow,deny'
        ];
    }
}
