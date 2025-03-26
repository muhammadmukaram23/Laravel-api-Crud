<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Http;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('create-user',[UserController::class,'createUser']);
Route::get('get-users',[UserController::class,'getUsers']);
Route::get('get-users_id/{id}',[UserController::class,'getUserById']);

Route::put('update-user/{id}', [UserController::class, 'updateUser']);
Route::delete('delete-user/{id}',[UserController::class,'deleteUSer']);