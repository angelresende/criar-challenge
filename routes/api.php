<?php

use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\{
    GroupController,
    ProductController,
    StateController,
    UserController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/users', UserController::class);
    Route::resource('/states', StateController::class);
    Route::resource('/groups', GroupController::class);
    Route::resource('/products', ProductController::class);
});


