<?php

use App\Http\Controllers\Dashboard\MealController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;




Route::get('/', function () {
    return view('welcome') ;
});

Route::get('/categories', function () {
    return view('pages.dashboard.categories.index') ;
});

Route::get('/meals', function () {
    return view('pages.dashboard.meal.index') ;
});


Route::get('test' , function(){
    return view('test') ;
});

Route::post('test/store' , [MealController::class , 'store'])->name('test.store') ;


require __DIR__.'/auth.php';
