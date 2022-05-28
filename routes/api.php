<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::post('/login', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {

	Route::controller(CartController::class)->group(function () {
		Route::get('cart', 'show');
		Route::post('cart', 'store');
		Route::patch('cart', 'update');
		Route::delete('cart', 'destroy');
	});

	Route::apiResource('categories', CategoryController::class);

	Route::apiResource('customers', CustomerController::class)->except('store');

	Route::apiResource('invitations', InvitationController::class);

	Route::apiResource('users', UserController::class)->except('store');

	Route::apiResource('products', ProductController::class);

	Route::apiResource('roles', RoleController::class);
});
