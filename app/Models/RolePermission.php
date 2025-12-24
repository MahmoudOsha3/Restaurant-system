<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;
    protected $fillable = ['permission' , 'role_id' , 'authorize'] ;
    protected $hidden = ['created_at' , 'updated_at' , 'role_id' , 'id' ];


}
