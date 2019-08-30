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
        Route::get('user/notification', 'API\UserController@user_notification_get');


        Route::get('invites', 'API\MailingController@invite');
        Route::get('invite/{id}', 'API\MailingController@invite_get');
        Route::post('invite/add', 'API\MailingController@invite_add');
        Route::post('invite/update', 'API\MailingController@invite_update');


        Route::get('custom', 'API\MailingController@custom');
        Route::get('custom/images/{id}', 'API\MailingController@custom_images');
        Route::post('favourite/add', 'API\MailingController@favourite_add');
        Route::post('custom/category/add', 'API\MailingController@custom_add');


        Route::get('contacts', 'API\ContactsController@contact_all');
        Route::get('contacts/mailing/{id}', 'API\ContactsController@contact');
        Route::get('contact/mailing/{id}/{mailing_id}', 'API\ContactsController@contact_get');
        Route::post('contact/mailing/add', 'API\ContactsController@contact_add');

        Route::get('mailing/send/{id}', 'API\ContactsController@send_mailing');

    });
});
