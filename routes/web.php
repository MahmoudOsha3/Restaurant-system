<?php

use App\Http\Controllers\Dashboard\InvoiceController;
use App\Http\Controllers\Dashboard\MealController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;




Route::get('/', function () {
    return view('pages.dashboard.home.index') ;
});

Route::get('/categories', function () {
    return view('pages.dashboard.categories.index') ;
});

Route::get('/meals', function () {
    return view('pages.dashboard.meal.index') ;
});

Route::get('/orders', function () {
    return view('pages.dashboard.orders.index') ;
});

Route::get('/admins', function () {
    return view('pages.dashboard.admins.index') ;
});

Route::get('/roles', function () {
    return view('pages.dashboard.rolesAndPermissions.index') ;
});

Route::get('/invoices', function () {
    return view('pages.dashboard.invoices.index') ;
});


Route::get('test' , function(){
    return view('test') ;
});

Route::post('test/store' , [InvoiceController::class , 'store'])->name('test.store') ;


require __DIR__.'/auth.php';
