<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id' , 'admin_id' , 'cookie_id' , 'meal_id' , 'quantity'] ;
    protected $hidden = ['created_at' , 'updated_at' ];
    public static function booted()
    {
        // Only Guest
        static::creating(function(Cart $cart){
            if (is_null($cart->user_id) && is_null($cart->admin_id)) {
                $cart->cookie_id = Cart::getCookieId();
            }
        });
    }

    public function scopeCashier(Builder $builder , $request)
    {
        $builder->when($request->meal_id , function($builder , $mealId){
            $builder->where('admin_id' , auth()->user()->id )->where('meal_id', $mealId) ;
        });
    }

    public static function getCookieId()
    {
        $cookieId = Cookie::get('cart_id') ;
        if(! $cookieId){
            $cookieId = (string) Str::uuid() ;
            Cookie::queue('cart_id' , $cookieId  , 60 * 24 * 30 ) ;
        }
        return $cookieId ;
    }




    // Realtionships
    public function meal()
    {
        return $this->belongsTo(Meal::class, 'meal_id') ;
    }

}
