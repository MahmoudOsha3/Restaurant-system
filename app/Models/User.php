<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable ;


    protected $fillable = [
        'name',
        'email',
        'password',
        'provider_id' ,
        'provider_type',
        'address' ,
        'city' ,
        'phone' ,
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed',
    ];

    public function scopeFilter(Builder $builder , $request)
    {
        $builder->when($request->search , function($builder , $value){
            $builder->where('phone','like','%'. $value .'%')
                ->orwhere('name' ,"like" ,"%$value%" );
        });
    }



    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id') ;
    }

}
