<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\{AdminController, AuthController, CartController, CategoryController , InvoiceController, MealController, OrderController, RoleController};
use App\Models\RolePermission;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login' , [AuthController::class , 'generateToken']) ;

// Route::middleware('auth:sanctum')->prefix('admin')->group(function(){
    Route::apiResource('admins' , AdminController::class) ;
    Route::apiResource('category' , CategoryController::class) ;
    Route::apiResource('meal' , MealController::class) ;
    Route::apiResource('role' , RoleController::class) ;
    Route::apiResource('cart' , CartController::class) ;
    Route::apiResource('orders' , OrderController::class) ;
    Route::get('invoice/{order_id}' , [InvoiceController::class , 'show'] )->name('invoice.show') ;
// });

