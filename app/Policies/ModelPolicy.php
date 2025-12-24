<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;

class ModelPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function __call($name, $arguments)
    {
        $class_name = str_replace('Policy' , '' , class_basename($this)) ; // MealPolicy => Meal
        $map = ['viewAny' => 'view' , 'view' => 'show'] ;
        $name = $map[$name] ?? $name;
        $permission = Str::lower($class_name . '.' . $name ) ;
        $admin = $arguments[0] ?? null  ;
        if(!$admin) return false ;
        return $admin->hasPermission($permission) ;
    }
}
