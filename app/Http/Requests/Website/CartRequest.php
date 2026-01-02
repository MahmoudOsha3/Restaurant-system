<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    public function authorize()
    {
        return true ;
    }

    public function rules()
    {
        $rules =  [
            'user_id' => 'nullable|exists:users,id',
            'quantity' => 'required|numeric|min:1|max:30',
        ];

        if ($this->isMethod('post')) {
            $rules['meal_id'] = 'required|exists:meals,id';
        }
        return $rules ;
    }
}
