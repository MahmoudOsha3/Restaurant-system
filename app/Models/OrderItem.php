<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id' , 'meal_id' , 'mea_title' , 'price' , 'quantity' , 'total'];


    public function meal()
    {
        return $this->belongsTo(Meal::class, 'meal_id');
    }
}
