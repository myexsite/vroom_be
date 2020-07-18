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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'UserController@index');
Route::post('signup', 'UserController@signup');
Route::post('reset-password', 'ResetPasswordController@send_email');
Route::post('change-password', 'ResetPasswordController@change_password');

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('users', 'UserController@users');
    Route::apiResources([
        'user-details' => 'UserDetailsController',
    ]);
});




