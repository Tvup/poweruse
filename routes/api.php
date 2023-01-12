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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('el/totalprice/{GLNNumber}', 'ElController@apiGetTotalPriceToday');
Route::get('el/Elspotprices', 'ElController@apiGetSpotprices');
Route::get('el/{refreshToken}', 'ElController@get');
Route::get('el/{refreshToken}/smartme', 'ElController@getWithSmartMe');
Route::get('el/charges/{refreshToken}', 'ElController@getCharges');
Route::get('el/{start_date}/{end_date}/{price_area}/{refreshToken}', 'ElController@getFromDate');
Route::get('el/{refreshToken}/delete', 'ElController@delete');

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResources(['meteringPoint' => 'Api\MeteringPointController']);
});