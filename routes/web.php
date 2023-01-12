<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    return redirect('el');
})->name('home');

Route::get(
    '/totalprices',
    App\Http\Controllers\TotalPricesController::class
)->name('totalprices');

Route::post(
    'totalprices',
    App\Http\Controllers\TotalPrices\ProcessController::class,
)->name('totalprices.process');

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::get('el/', 'ElController@index')->name('el');
Route::get('el-meteringpoint/', 'ElController@indexMeteringPoint')->name('el-meteringpoint');
Route::get('metering-point/', 'MeteringPointController@index')->name('metering-point');
Route::get('el-charges/', 'ElController@indexCharges')->name('el-charges');
Route::get('el-spotprices/', 'ElController@indexSpotprices')->name('el-spotprices');
Route::get('consumption/', 'ElController@indexConsumption')->name('consumption');
Route::get('el-custom/', 'ElController@indexCustomUsage')->name('el-custom');
Route::post('processdata', 'ElController@processData');
Route::post('getMeteringPointData', 'ElController@getMeteringPointData');
Route::post('getChargesForWeb', 'ElController@getChargesForWeb');
Route::post('getSpotprices', 'ElController@getSpotprices');
Route::post('getConsumption', 'ElController@getConsumption');
Route::post('getTotalPrices', 'ElController@getTotalPrices');
Route::post('processcustom', 'ElController@processCustom');




require __DIR__.'/auth.php';
