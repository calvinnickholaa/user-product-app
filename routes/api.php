<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', [
        App\Http\Controllers\API\Auth\AuthController::class,
        'logout'
    ]);
});
Route::post('register', [
    App\Http\Controllers\API\Auth\AuthController::class,
    'register'
]);
Route::post('login', [
    App\Http\Controllers\API\Auth\AuthController::class,
    'login'
]);

Route::get('list-product', [
    App\Http\Controllers\API\ProductController::class,
    'index'
]);
Route::post('store-product', [
    App\Http\Controllers\API\ProductController::class,
    'store'
]);
Route::get('read-product/{id}', [
    App\Http\Controllers\API\ProductController::class,
    'show'
]);
Route::put('update-product/{id}', [
    App\Http\Controllers\API\ProductController::class,
    'update'
]);
Route::delete('delete-product/{id}', [
    App\Http\Controllers\API\ProductController::class,
    'destroy'
]);

Route::get('list-user', [
    App\Http\Controllers\API\UserController::class,
    'index'
]);
Route::get('show-user/{id}', [
    App\Http\Controllers\API\UserController::class,
    'show'
]);
Route::delete('delete-user/{id}', [
    App\Http\Controllers\API\UserController::class,
    'destroy'
]);
