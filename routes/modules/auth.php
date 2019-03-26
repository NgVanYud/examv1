<?php
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 3/25/19
 * Time: 3:37 PM
 */
Route::group([
    'prefix' => 'auth',
    'namespace' => 'Auth'
], function() {
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LogoutController@logout');
    Route::post('refresh', 'AuthController@refresh');
});
