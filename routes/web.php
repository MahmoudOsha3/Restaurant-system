<?php

use App\Http\Controllers\Website\{CartController,HomeController, OrderController, ProfileController};
use App\Http\Controllers\Website\{AuthController , SocialiteController , ResetPasswordController};
use Illuminate\Support\Facades\Route;

    Route::middleware('web')->group(function(){
        Route::get('carts' , [CartController::class , 'getCarts']);
        Route::get('/' , [HomeController::class , 'home'])->name('home');
        Route::get('order/checkout' , [OrderController::class , 'checkout'])->name('order.checkout');
        Route::post('user/order' , [OrderController::class , 'store'])->name('order.store');

        // Authentication system
        Route::prefix('user/profile')->name('user.')->controller(ProfileController::class)->group(function(){
            Route::get('/' , 'profile')->name('profile');
            Route::put('update' , 'update')->name('profile.update') ;
        });
        Route::post('auth/logout' ,[AuthController::class , 'logout'])->name('auth.logout') ;
    }) ;

    // Authentication system

    // login using (system)
    Route::prefix('auth')->middleware('guest')->name('auth.')->controller(AuthController::class)->group(function () {

        Route::get('login', 'login')->name('login');
        Route::post('login', 'checkLogin')->middleware('throttle:5,1')->name('check');

        Route::get('register', 'register')->name('create');
        Route::post('create', 'createUser')->name('store');
    });

    // login using (Google and github)
    Route::prefix('app')->name('socialite.')->controller(SocialiteController::class)->group(function(){
        Route::get('login/{provider}' , 'login')
            ->where('provider/{provider}' , 'github|google')->name('login') ;

        Route::get('redirect/{provider}' , 'redirect')->name('redirect')
            ->where('provider', 'github|google');
    });

    // Reset password
    Route::prefix('auth')->middleware('guest')->controller(ResetPasswordController::class)->group(function (){

        Route::get('reset' , 'index' )->name('reset.password.view');
        Route::post('reset/password' , 'reset' )->name('reset.password');

        Route::get('reset-password/{token}','showResetForm')
            ->name('password.reset');

        Route::post('reset-password', 'update')
            ->name('password.update');
    });
