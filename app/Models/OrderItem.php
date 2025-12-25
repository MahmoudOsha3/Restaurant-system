<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id' , 'meal_id' , 'meal_title' , 'price' , 'quantity' , 'total'];


    public function meal()
    {
        return $this->belongsTo(Meal::class, 'meal_id');
    }
}
