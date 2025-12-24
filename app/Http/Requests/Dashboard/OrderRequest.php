<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 'user_id' => 'nullable|exists:users,id' ,
            // 'admin_id' => 'required|exists:admins,id' ,
        ];
    }
}
