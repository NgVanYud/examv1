<?php
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 3/26/19
 * Time: 10:07 AM
 */
Route::group([
  'middleware' => 'jwt.auth',
  'namespace' => 'API'
], function() {
    Route::get('me', 'UserController@me');
    Route::apiResource('users', 'UserController');
});
