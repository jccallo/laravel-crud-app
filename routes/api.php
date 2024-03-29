<?php

use App\Http\Controllers\api\BrandController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::apiResource('tags', TagController::class);
Route::apiResource('brands', BrandController::class);
Route::resource('products', ProductController::class)->except('edit');
Route::resource('customers', CustomerController::class);
