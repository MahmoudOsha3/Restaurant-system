<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
                'name' => 'required|min:8' ,
                'price'=> 'required'
        ];
    }
    public function messages() :array
    {
        return [
            'name.required'  => "الاسم مطلوب" ,
            'name.min'  => "الاسم لايجب ان يقل عن 8 احرف " ,
            'price.required' => "السعر مطلوب"
        ];
    }
}
