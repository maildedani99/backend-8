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
Route::get('products/all', 'App\Http\Controllers\ProductController@all');
Route::get('products/{id}', 'App\Http\Controllers\ProductController@getById');
Route::post('products/delete/{id}', 'App\Http\Controllers\ProductController@delete');
Route::post('products/create', 'App\Http\Controllers\ProductController@create');
//Route::get('products/outlet', 'App\Http\Controllers\ProductController@outlet');
Route::get('products/getBySubCategory/{category_id}', 'App\Http\Controllers\ProductController@getBySubCategory');
Route::get('products/novelties/all', 'App\Http\Controllers\ProductController@novelties');
Route::get('products/outlet/all',  'App\Http\Controllers\ProductController@outlet' );


// images routes
Route::get('images/all', 'App\Http\Controllers\ImageController@all');
Route::post('images/create', 'App\Http\Controllers\ImageController@create');

// categories routes
Route::get('categories/all', 'App\Http\Controllers\CategoryController@all');
Route::post('categories/create', 'App\Http\Controllers\CategoryController@create');
Route::post('categories/delete/{id}', 'App\Http\Controllers\CategoryController@delete');

// novelties routes
Route::post('novelties/create', 'App\Http\Controllers\NoveltyController@create');
Route::get('novelties/all', 'App\Http\Controllers\NoveltyController@all');

// subcategories routes
Route::get('subcategories/all', 'App\Http\Controllers\SubcategoryController@all');
Route::get('subcategories/getById/{id}', 'App\Http\Controllers\SubcategoryController@getById');
Route::post('subcategories/create', 'App\Http\Controllers\SubcategoryController@create');
Route::post('subcategories/delete/{id}', 'App\Http\Controllers\SubcategoryController@delete');

// sizes routes
Route::get('sizes/all', 'App\Http\Controllers\SizeController@all');
Route::post('sizes/create', 'App\Http\Controllers\SizeController@create');
Route::post('sizes/delete/{id}', 'App\Http\Controllers\SizeController@delete');





