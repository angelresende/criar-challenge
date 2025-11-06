<?php

use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\{
    CampaignController,
    CityController,
    DiscountController,
    GroupController,
    ProductController,
    StateController,
    UserController,
};

use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/users', UserController::class);
    Route::resource('/states', StateController::class);
    Route::resource('/groups', GroupController::class);
    Route::resource('/cities', CityController::class);
    Route::resource('/campaigns', CampaignController::class);
    Route::resource('/discounts', DiscountController::class);
    Route::resource('/products', ProductController::class);

});


