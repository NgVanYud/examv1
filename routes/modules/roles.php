<?php
Route::group([
  'middleware' => ['jwt.auth', 'role:'.config('access.roles_list.admin')],
  'namespace' => 'API'
], function() {
  Route::get('roles/teachers', 'RoleController@getTeacher');
  Route::apiResource('roles', 'RoleController');
});
