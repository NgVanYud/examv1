<?php
Route::group([
  'middleware' => 'jwt.auth',
  'namespace' => 'API'
], function() {
  Route::apiResource('roles', 'RoleController');
});
