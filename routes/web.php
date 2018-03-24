<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => 'user'], function () {
    Route::get('store', 'UserController@store');
    Route::post('login', 'UserController@login');
    Route::post('get_user_info', 'UserController@get');
});
Route::post('/product/create', 'ProductController@createProduct');
Route::post('/products/category', 'ProductController@getProductsByCatagory');
Route::post('/productdetail', 'ProductController@getProductDetail');
Route::post('/getCommentsByProduct', 'CommentController@getCommentsByProduct');
Route::post('/postCommentProduct', 'CommentController@postCommentProduct');
Route::post('/like/product', 'LikeController@likeProduct');
Route::post('/unlike/product', 'LikeController@unlikeProduct');
Route::post('/products/seller', 'ProductController@getProductsByUser');
Route::post('/products/favorite', 'ProductController@getProductsFavoriteByUser');
Route::post('/product/buy','ProductController@buyProduct');
Route::post('/product/sell','ProductController@sellProduct');
Route::post('/product/cancel/sell','ProductController@cancelSellProduct');
Route::post('/product/sell/processing','ProductController@getProductSellProcessing');
Route::post('/product/sell/success','ProductController@getProductSellSuccess');
Route::post('/product/buy/processing','ProductController@getProductBuyProcessing');
Route::post('/product/buy/success','ProductController@getProductBuySuccess');
Route::group(['prefix' => 'conversation'], function () {
    Route::post('messages', 'ConversationController@getMessagesConversation');
    Route::post('set_message', 'ConversationController@setMessageConversation');
});
Route::post('/category/get', 'CategoryController@getCategories');
Route::group(['prefix' => 'address'], function () {
    Route::post('get', 'AddressOrderController@getAddressSeller');
    Route::post('insert', 'AddressOrderController@setAddressSeller');
    Route::post('district', 'AddressOrderController@getDistricts');
    Route::post('district/search', 'AddressOrderController@searchDistrict');
    Route::post('village', 'AddressOrderController@getVillages');
    Route::post('village/search', 'AddressOrderController@searchVillage');
});
Route::group(['prefix' => 'upload/image'], function () {
    Route::post('product', 'ImageController@uploadImageProduct');
    Route::post('user', 'ImageController@uploadImageUser');
});
Route::group(['prefix'=>'news'],function (){
   Route::post('get','NewsController@getNews');
});