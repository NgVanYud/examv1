<?php
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 3/26/19
 * Time: 11:42 AM
 */
Route::group([
    'middleware' => 'jwt.auth',
  'namespace' => 'API'
], function() {
    Route::apiResource('terms', 'TermController');
});
