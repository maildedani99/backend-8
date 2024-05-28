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

    Route::post('products/create', 'App\Http\Controllers\ProductController@create');
    Route::post('images/create', 'App\Http\Controllers\ImageController@create');
    Route::post('categories/create', 'App\Http\Controllers\CategoryController@create');
    Route::post('novelties/create', 'App\Http\Controllers\NoveltyController@create');
    Route::post('subcategories/create', 'App\Http\Controllers\SubcategoryController@create');
    Route::post('subcategories/delete/{id}', 'App\Http\Controllers\SubcategoryController@delete');
    Route::post('categories/delete/{id}', 'App\Http\Controllers\CategoryController@delete');
    Route::post('products/delete/{id}', 'App\Http\Controllers\ProductController@delete');
    Route::post('sizes/create', 'App\Http\Controllers\SizeController@create');
    Route::post('sizes/delete/{id}', 'App\Http\Controllers\SizeController@delete');
    Route::post('colors/create', 'App\Http\Controllers\ColorController@create');
    Route::post('colors/delete/{id}', 'App\Http\Controllers\ColorController@delete');
    Route::post('stock/create', 'App\Http\Controllers\StockController@create');
    Route::post('stock/delete/{id}', 'App\Http\Controllers\StockController@delete');
    Route::post('email/delete', 'App\Http\Controllers\EmailController@delete');
    Route::post('customers/create', 'App\Http\Controllers\CustomerController@create');
    Route::post('customers/delete', 'App\Http\Controllers\CustomerController@delete');
    Route::post('orders/create', 'App\Http\Controllers\OrderController@create');
    Route::post('orders/delete', 'App\Http\Controllers\OrderController@delete');
});

// users routes
Route::post('register', 'App\Http\Controllers\UserController@register');
Route::post('login', 'App\Http\Controllers\UserController@authenticate');


// products routes
Route::get('products/all', 'App\Http\Controllers\ProductController@all');
Route::get('products/allStock', 'App\Http\Controllers\ProductController@allStock');
Route::get('products/{id}', 'App\Http\Controllers\ProductController@getById');
Route::get('products/getBySubCategory/{category_id}', 'App\Http\Controllers\ProductController@getBySubCategory');
Route::get('products/novelties/all', 'App\Http\Controllers\ProductController@novelties');
Route::get('products/outlet/all',  'App\Http\Controllers\ProductController@outlet');
Route::get('products/discounts/all', 'App\Http\Controllers\ProductController@discounts');

// outlet routes
Route::get('outlet/all', 'App\Http\Controllers\NOutletController@all');

// images routes
Route::get('images/all', 'App\Http\Controllers\ImageController@all');

// categories routes
Route::get('categories/all', 'App\Http\Controllers\CategoryController@all');

// novelties routes
Route::get('novelties/all', 'App\Http\Controllers\NoveltyController@all');

// subcategories routes
Route::get('subcategories/all', 'App\Http\Controllers\SubcategoryController@all');
Route::get('subcategories/getById/{id}', 'App\Http\Controllers\SubcategoryController@getById');

// sizes routes
Route::get('sizes/all', 'App\Http\Controllers\SizeController@all');


// colors routes
Route::get('colors/all', 'App\Http\Controllers\ColorController@all');


// Stock routes
Route::get('stock/all', 'App\Http\Controllers\StockController@all');



// email routes

Route::get('email/all', 'App\Http\Controllers\EmailController@all');
Route::post('email/create', 'App\Http\Controllers\EmailController@create');


// customers routes

Route::get('customers/all', 'App\Http\Controllers\CustomerController@all');


// orders routes

Route::get('orders/all', 'App\Http\Controllers\OrderController@all');
Route::post('orders/completeOrderProcess', 'App\Http\Controllers\OrderProcessController@completeOrderProcess');


//  orderItems routes

Route::get('orderItems/all', 'App\Http\Controllers\OrderItemController@all');
Route::get('orderItems/getItemsByOrderId/{orderId}', 'App\Http\Controllers\OrderItemController@getItemsByOrderId');

Route::post('orderItems/addItemsToOrder', 'App\Http\Controllers\OrderItemController@addItemsToOrder');
Route::post('orderItems/delete', 'App\Http\Controllers\OrderItemController@delete');

// redsys routes
Route::post('redsys/generate-signature', 'App\Http\Controllers\RedsysController@generateSignature');
Route::post('redsys/handle-notification', 'App\Http\Controllers\RedsysController@handleNotification');

Route::post('redsys/handle-notificationOk', 'App\Http\Controllers\RedsysController@handleNotificationOk');




Route::options('/api/email/create', function () {
    return response()->json([], 200);
});
