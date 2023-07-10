<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:api')->group( function () {
    Route::resource('product', ProductController::class);
});
Route::post('product/add-to-basket/{id}', [ProductController::class, 'addToBasket']);
Route::delete('product/remove-from-basket/{id}', [ProductController::class, 'removeFromBasket']);
