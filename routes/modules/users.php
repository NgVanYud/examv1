<?php
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 3/26/19
 * Time: 10:07 AM
 */
Route::group([
  'middleware' => ['assign.guard:student', 'jwt.auth'],
  'namespace' => 'API'
], function () {
  //  student
  Route::post('student/me', 'UserController@me');
});

// Manager
Route::group([
  'middleware' => ['assign.guard:manager', 'jwt.auth'],
  'namespace' => 'API'
], function () {
  Route::post('manager/me', 'UserController@me');
  Route::post('users/store-multiple', 'UserController@storeMulti');
  Route::post('users/active', 'UserController@active')->middleware('role:' . config('access.roles_list.admin'));
  Route::post('users/deactive', 'UserController@deactive')->middleware('role:' . config('access.roles_list.admin'));
  Route::get('users/teachers', 'UserController@getTeacher')->middleware('role:' . config('access.roles_list.admin'));
  Route::post('users/by-role', 'RoleController@getUsersByRoleName')->middleware('role:' . config('access.roles_list.curator') . '|' . config('access.roles_list.admin'));
  Route::apiResource('managers', 'ManagerController')->middleware('role:' . config('access.roles_list.admin'));
});
