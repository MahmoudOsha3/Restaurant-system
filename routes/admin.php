<?php

use App\Http\Controllers\Dashboard\AuthController ;
use App\Http\Controllers\Dashboard\ManageRouteController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:admin'])->group(function(){

    Route::controller(ManageRouteController::class)->group(function (){
        Route::get('/dashboard' , 'dashboard')->name('dashboard') ;
        Route::get('/categories' , 'categories') ;
        Route::get('/meals' , 'meals') ;
        Route::get('/orders' , 'orders') ;
        Route::get('/admins' , 'admins') ;
        Route::get('/roles' , 'roles') ;
        Route::get('/invoices' , 'invoices') ;
    });
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

    Route::get('/login', [AuthController::class, 'loginView']);
    Route::post('/login', [AuthController::class, 'login'])->name('login')->middleware('throttle:5,1');





