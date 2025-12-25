<?php

namespace App\Providers;

use App\Interfaces\{CartRepositoryInterface , InvoiceRepositoryInterface, MealRepositoryInterface , OrderRepositoryInterface ,OrderItemRepositoryInterface};
use App\Repositories\{MealRepository};
use App\Repositories\Dashboard\{CartRepository , InvoiceRepository, OrderRepository , OrderItemRepository};

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MealRepositoryInterface::class , MealRepository::class);
        $this->app->bind(CartRepositoryInterface::class , CartRepository::class ) ;
        $this->app->bind(OrderRepositoryInterface::class , OrderRepository::class ) ;
        $this->app->bind(OrderItemRepositoryInterface::class , OrderItemRepository::class ) ;
        $this->app->bind(InvoiceRepositoryInterface::class , InvoiceRepository::class ) ;


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
