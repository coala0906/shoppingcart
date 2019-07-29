<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
i contains the "web" middleware group. Now create something great!
|
*/

Route::get('/shoppingcart', function () {
    return view('welcome');
});
Route::get('/shoppingcart/login', function () {
    return view('welcome');
});
Route::get('/shoppingcart/manage/transaction', function () {
    return view('welcome');
});
Route::get('/shoppingcart/manage/member', function () {
    return view('welcome');
});
Route::get('/shoppingcart/manage/item', function () {
    return view('welcome');
});
Route::get('/shoppingcart/cart', function (){
    return view('welcome');
});
Route::get('/shoppingcart/purchase', function (){
    return view('welcome');
});
Route::get('/shoppingcart/meminfo', function (){
    return view('welcome');
});

Route::get('api/member/','Shoppingcart\Member@getList');
Route::get('api/member/{id}','Shoppingcart\Member@getMember');
Route::post('api/member/','Shoppingcart\Member@createMember');
Route::put('api/member/{id}','Shoppingcart\Member@putMember');
Route::get('api/cart/','Shoppingcart\Cart@getList');
Route::post('api/cart/','Shoppingcart\Cart@createCart');
Route::put('api/cart/','Shoppingcart\Cart@putCart');
Route::post('api/cart/{id}','Shoppingcart\Cart@delCart');
Route::get('api/product/{page}','Shoppingcart\Product@getList');
Route::get('api/product/item/{id}','Shoppingcart\Product@getProduct');
Route::post('api/product/','Shoppingcart\Product@createProduct');
Route::post('api/product/put/{id}','Shoppingcart\Product@putProduct');
Route::post('api/session/','Shoppingcart\Session_new@login');
Route::get('api/session/','Shoppingcart\Session_new@getUser');
Route::post('api/session/logout','Shoppingcart\Session_new@logout');
Route::get('api/order/{page}','Shoppingcart\Transaction@getOrderList');
Route::get('api/transaction/','Shoppingcart\Transaction@getList');
Route::get('api/transaction/detail/{id}','Shoppingcart\Transaction@getOrderDetail');
Route::put('api/transaction/','Shoppingcart\Transaction@putOrderStatus');
Route::post('api/transaction/','Shoppingcart\Transaction@createTransaction');
