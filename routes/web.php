<?php

use App\Http\Controllers\Website\{CartController,HomeController, MenuController, OrderController, PaymentController, ProfileController};
use App\Http\Controllers\Website\{AuthController , SocialiteController , ResetPasswordController};
use Illuminate\Support\Facades\Route;

    Route::get('/' , [HomeController::class , 'home'])->name('home');
    Route::get('carts' , [CartController::class , 'getCarts']);
    Route::get('menu' , [MenuController::class , 'index'])->name('menu.index');

    Route::middleware('auth')->group(function(){

        Route::prefix('user')->group(function(){
            Route::get('carts' , [CartController::class , 'index'])->name('carts.index');

            Route::post('order/store' , [OrderController::class , 'store'])->name('order.store');
            Route::get('orders' , [OrderController::class , 'orders'])->name('orders.checkout');
            Route::post('order/payment/{order_id}' , [PaymentController::class , 'pay'])->name('order.payment');
        });

        Route::prefix('payment')->controller(PaymentController::class)->group(function(){
            Route::post('webhook/{gateway}' , 'webhook')->name('order.payment.webhook');
            Route::get('callback/{gateway}' , 'callback')->name('order.payment.callback');
        });


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
