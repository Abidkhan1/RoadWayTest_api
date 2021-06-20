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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/// JWT new APIs
Route::group(['prefix'=>'auth','namespace'=>'App\Http\Controllers\Auth'], function(){
  Route::post('test','ApiAuthController@index');
  Route::post('signin','SignInController@index');
  // Route::post('signout','SignOutController@index');
  Route::get('logout','SignInController@logout');
  Route::get('me','SignInController@me');
});
/// JWT new APIs

Route::group(['namespace'=>'App\Http\Controllers\Todo'], function(){
  Route::apiResource('todos', ApiTodoController::class);
});
