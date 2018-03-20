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
Route::post('/products/category','ProductController@getProductsByCatagory');
Route::post('/productdetail','ProductController@getProductDetail');
Route::post('/getCommentsByProduct','CommentController@getCommentsByProduct');
Route::post('/postCommentProduct','CommentController@postCommentProduct');
Route::post('/like/product','LikeController@likeProduct');
Route::post('/unlike/product','LikeController@unlikeProduct');
Route::post('/products/seller','ProductController@getProductsByUser');
Route::post('/products/favorite','ProductController@getProductsFavoriteByUser');
Route::group(['prefix'=>'conversation'],function (){
    Route::post('messages','ConversationController@getMessagesConversation');
    Route::post('set_message','ConversationController@setMessageConversation');
});
Route::post('/category/get','CategoryController@getCategories');
Route::group(['prefix'=>'address'],function (){
   Route::post('get','AddressOrderController@getAddressSeller') ;
   Route::post('set','AddressOrderController@setAddressSeller');
});
Route::group(['prefix'=>'upload/image'],function (){
    Route::post('product','ImageController@uploadImageProduct');
    Route::post('user','ImageController@uploadImageUser');
});