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

//Route::get('/', function () {
//    return view('welcome');
//});
//Route::get('password/email/{email}', [Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email.get');

Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset')->middleware('assign.guard:manager');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset.form');


Route::group(['middleware' => 'web'], function () {
  Route::get('/{any}', 'LaravueController@index')->where('any', '.*');
});

//Route::auth();
