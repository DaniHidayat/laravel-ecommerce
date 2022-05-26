<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::middleware('auth:sanctum')
	->group(function () {
		Route::apiResource('categories', CategoryController::class);

		Route::apiResource('customers', CustomerController::class)->except('store');

		Route::apiResource('users', UserController::class)->except('store');

		Route::apiResource('products', ProductController::class);
	});
