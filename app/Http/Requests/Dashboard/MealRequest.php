<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class MealRequest extends FormRequest
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
            'title' => 'required|string|max:255' ,
            'description' => 'required|string|max:500',
            'price' => 'required|numeric' ,
            'compare_price' => 'nullable|numeric' ,
            'image' => 'required|image' ,
            'status'=> 'nullable|in:active,inactive',
            'preparation_time' => 'required|integer' ,
            'category_id' => 'required|exists:categories,id'
        ];
    }
}
