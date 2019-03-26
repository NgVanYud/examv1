<?php
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 3/26/19
 * Time: 10:07 AM
 */
Route::group([
    'middleware' => 'jwt.auth'
], function() {
    Route::post('me', 'UserController@me');
});
