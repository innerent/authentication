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

// Public owner registration
Route::post('login', 'AuthController@issueToken')->middleware('api')->name('innerent.login');
Route::post('logout', 'AuthController@revokeToken')->middleware('auth:api')->name('innerent.logout');

Route::group([
    'middleware' => 'auth:api',
    'prefix' => ''
], function () {
    Route::apiResource('users', 'UserController', ['as' => 'innerent']);
    Route::get('user', 'UserController@loggedUser')->name('innerent.user');
    Route::get('auth/email/resend', 'VerificationController@resend')->name('verification.resend');
});

Route::group([
    'middleware' => 'api', // No authentication
    'prefix' => 'auth'
], function () {

    Route::get('email/verify', 'VerificationController@show')->name('verification.notice');
    Route::get('email/verify/{uuid}', 'VerificationController@verify')->name('verification.verify');

    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');
});
