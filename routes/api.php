<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'v1',
    'middleware' => 'cors'
], function () {
    Route::post('login', 'API\AuthController@login');
    Route::post('signup', 'API\AuthController@signup');
    Route::post('reset_email', 'API\AuthController@reset_email');
    Route::post('password_reset', 'API\AuthController@password_reset');

  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'API\AuthController@logout');
        Route::get('user', 'API\AuthController@user');
        Route::post('user/update', 'API\UserController@user_update');
        Route::post('user/update/password', 'API\UserController@user_update_password');
        Route::post('user/notification/add', 'API\UserController@user_notification_add');

    });
});
