<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable ;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id' ,
        'address' ,
        'phone' ,
        'image'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'image'
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed',
    ];


    public function hasPermission($permission)
    {
        return RolePermission::where([
            'role_id' => $this->role_id ,
            'permission' => $permission ,
            'authorize' => 'allow'
        ])->exists() ; // boolean (true / false)
    }

    public function role()
    {
        return $this->belongsTo(Role::class , 'role_id');
    }



}
