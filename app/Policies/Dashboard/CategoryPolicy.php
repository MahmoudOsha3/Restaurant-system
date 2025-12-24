<?php

namespace App\Policies\Dashboard;

use App\Models\User;
use App\Policies\ModelPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy extends ModelPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
