<?php

use App\Http\Controllers\Admin\Dashboardcontroller;
use App\Http\Controllers\Dashboard\CategoriesController;
use Illuminate\Support\Facades\Route;



Route::get('/dashboard',[Dashboardcontroller::class , 'index'])
    ->middleware(['auth'])      //middleware(['auth', 'verified'])  untill verifying
    ->name('dashboard');


Route::resource('dashboard/categories' , CategoriesController::class);

