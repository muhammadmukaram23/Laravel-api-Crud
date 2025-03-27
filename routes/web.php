<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserViewController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/users', [UserViewController::class, 'index']);