<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Meal;
use App\Policies\Dashboard\AdminPolicy;
use App\Policies\Dashboard\CategoryPolicy;
use App\Policies\Dashboard\DashboardPolicy;
use App\Policies\Dashboard\MealPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Category::class => CategoryPolicy::class,
        Admin::class => AdminPolicy::class,
        Meal::class => MealPolicy::class,
        Admin::class => DashboardPolicy::class ,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
