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
    Route::get('subject-term/detail', 'SubjectTermController@show');
    Route::post('subject-term/{termId}/{subjectId}/setting', 'SubjectTermController@setting');
    Route::apiResource('terms', 'TermController');
});
