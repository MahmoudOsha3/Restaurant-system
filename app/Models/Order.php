<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id' , 'admin_id' , 'order_number' ,
        'status' , 'type' , 'subtotal' , 'tax' ,
        'delivet_fee' , 'total'];


    public static function booted()
    {
        static::creating(function(Order $order){
            $order->order_number = Order::getNextNumberOrder();
        });
    }


    public function scopeFilter(Builder $builder , $request)
    {
        $builder->when($request->order_number , function ($builder, $order_number){
            $builder->where('order_number' ,'LIKE' , "%{$order_number}%") ;
        });
    }



    public static function getNextNumberOrder()
    {
        $year = Carbon::now()->year ;
        $latest_order = Order::whereYear('created_at' , $year )->max('order_number') ;
        if($latest_order){
            return $latest_order + 1 ;
        }
        return $year . '0001' ;
    }


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class , 'order_id') ;
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class , 'admin_id') ;
    }


}
