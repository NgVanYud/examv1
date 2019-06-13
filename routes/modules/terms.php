<?php
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 3/26/19
 * Time: 11:42 AM
 */
Route::group([
    'middleware' => ['jwt.auth', 'role:'.config('access.roles_list.curator').'|'.config('access.roles_list.protor')],
  'namespace' => 'API'
], function() {
    Route::get('subject-term/detail', 'SubjectTermController@show');
    Route::post('subject-term/{termId}/{subjectId}/setting', 'SubjectTermController@setting');
    Route::post('subject-term/subject', 'SubjectTermController@subjectsForTerm')->middleware('role:'.config('access.roles_list.protor'));
    Route::apiResource('terms', 'TermController');
});
