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
    Route::post('users/store-multiple', 'UserController@storeMulti');
    Route::post('users/active', 'UserController@active')->middleware('role:'.config('access.roles_list.admin'));
    Route::post('users/deactive', 'UserController@deactive')->middleware('role:'.config('access.roles_list.admin'));
    Route::get('users/teachers', 'UserController@getTeacher')->middleware('role:'.config('access.roles_list.admin'));
    Route::apiResource('users', 'UserController')->middleware('role:'.config('access.roles_list.admin'));
});
