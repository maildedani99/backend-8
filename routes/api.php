<?php

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('user', 'App\Http\Controllers\UserController@getAuthenticatedUser');
});

// users routes

Route::post('register', 'App\Http\Controllers\UserController@register');
Route::post('login', 'App\Http\Controllers\UserController@authenticate');
// products routes
Route::get('product/all', 'App\Http\Controllers\ProductController@all');
Route::get('product/{id}', 'App\Http\Controllers\ProductController@getById');

Route::post('product/create', 'App\Http\Controllers\ProductController@create');

// images routes
Route::get('images/all', 'App\Http\Controllers\ImageController@all');
Route::post('images/create', 'App\Http\Controllers\ImageController@create');
