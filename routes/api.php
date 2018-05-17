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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::Group(['prefix' => 'v1','namespace' => 'api\v1'],function (){
    Route::post('Register','RegisterController@store');
    Route::post('Login','RegisterController@checkpass');
    Route::post('CheckRegister','RegisterController@checkphone');
    Route::post('GetVerificationCode','RegisterController@getcode');
    Route::post('ForgetPass','RegisterController@forgetpass');
    Route::post('ConTest','RegisterController@contest');

//    Route::get('Test','GoodsController@getmenu');
}
);

Route::group(['prefix' => 'v1','namespace' => 'api\v1','middleware' => 'jwt.auth'],function(){
    Route::get('GetData','RegisterController@index');
    Route::post('GetData','RegisterController@getdata');
    Route::post('ChangePass','RegisterController@chanepass');
    Route::post('WalletCharge','RegisterController@walletcharge');
    Route::post('UpdateProfile','RegisterController@updateprofile');

    Route::post('GetMenu','GoodsController@getmenu');
});