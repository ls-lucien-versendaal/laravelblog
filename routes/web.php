<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PagesController::class,'index']);
Route::resource('/blog',PostController::class);

Auth::routes();




