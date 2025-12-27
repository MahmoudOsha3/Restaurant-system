<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\{AdminController, AuthController, CartController, CategoryController , HomeController, InvoiceController, MealController, OrderController, RoleController};



Route::middleware('auth:sanctum')->group(function(){
    Route::get('/' , [HomeController::class , 'index']) ;
    Route::apiResource('admins' , AdminController::class) ;
    Route::apiResource('category' , CategoryController::class);
    Route::apiResource('meal' , MealController::class) ;
    Route::apiResource('role' , RoleController::class) ;
    Route::get('get/roles' , [RoleController::class , 'getRoles']) ;
    Route::apiResource('cart' , CartController::class) ;
    Route::apiResource('orders' , OrderController::class) ;
    Route::apiResource('invoice' , InvoiceController::class) ;
});

