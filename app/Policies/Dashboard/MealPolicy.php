<?php

namespace App\Policies\Dashboard;

use App\Models\User;
use App\Policies\ModelPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class MealPolicy extends ModelPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }
}
