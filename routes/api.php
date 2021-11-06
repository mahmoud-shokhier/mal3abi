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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('testapi', function () {
    return 'hello';
});

Route::post('verify-register-token', 'API\\AuthController@verifyRegisterToken');

Route::post('sign-up', 'API\\AuthController@signup');
Route::post('sign-in', 'API\\AuthController@signin');
Route::post('reset-password', 'API\\AuthController@resetPassword');
Route::post('reset-password/change', 'API\\AuthController@resetPasswordChange');

Route::get('playground', 'API\\PlaygroundController@index');
Route::get('playground/{playground_id}', 'API\\PlaygroundController@show');

Route::group(['middleware' => ['auth:api', 'role:user,playground']], function () {
    Route::get('profile', 'API\\AuthController@profile');
    Route::patch('profile', 'API\\AuthController@UpdateProfile');
    Route::delete('profile', 'API\\AuthController@deleteProfile');
    //payment redirect url
   
    Route::get('payment/get', 'API\\PaymentController@getTransactionStatus');
});

Route::group(['middleware' => ['auth:api', 'role:playground']], function () {
    Route::delete('playground/images', 'API\\PlaygroundController@deleteImage');
});

Route::post('payment/redirect', 'API\\PaymentController@paymentRedirect')->middleware('ValidateCowpaySignature');
Route::get('cowpay', 'API\\PaymentController@renderCowpay')->middleware('auth:api');

Route::post('reservation', 'API\\ReservationsController@store')->middleware('auth:api');
Route::get('reservation/{id}', 'API\\ReservationsController@show');
Route::put('reservation/{reservation_id}', 'API\\ReservationsController@update')->middleware('auth:api');
Route::get('reservation', 'API\\ReservationsController@index');
Route::delete('reservation/{id}', 'API\\ReservationsController@delete');

Route::get('profile/playgrounds', 'API\\ReservationsController@getPlaygrounds')->middleware(['auth:api', 'role:user']);
Route::get('profile/users', 'API\\PlaygroundController@getUsers')->middleware(['auth:api', 'role:playground']);


Route::post('playground/rate', 'API\\PlaygroundController@setRate')->middleware(['auth:api']);
Route::get('options', 'API\\OptionsController');
