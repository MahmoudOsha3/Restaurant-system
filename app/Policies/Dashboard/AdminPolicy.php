<?php

namespace App\Policies\Dashboard;

use App\Models\Admin;
use App\Policies\ModelPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy extends ModelPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

}
