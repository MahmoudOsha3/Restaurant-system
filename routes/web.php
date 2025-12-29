<?php

use App\Http\Controllers\Website\AuthController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\ProfileController;
use App\Http\Controllers\Website\SocialiteController;
use Illuminate\Support\Facades\Route;



    Route::middleware('web')->group(function(){

        Route::get('/' , [HomeController::class , 'home'])->name('home') ;

        Route::prefix('user/profile')->name('user.')->controller(ProfileController::class)->group(function(){
            Route::get('/' , 'profile')->name('profile');
            Route::put('update' , 'update')->name('profile.update') ;
        });

        Route::post('auth/logout' ,[AuthController::class , 'logout'])->name('auth.logout') ;

    });

    // Authentication system

    // login using (system)
    Route::prefix('auth')->middleware('guest')->name('auth.')->controller(AuthController::class)->group(function () {

        Route::get('login', 'login')->name('login');
        Route::post('login', 'checkLogin')->name('check');

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
