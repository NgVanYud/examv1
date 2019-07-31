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
    Route::get('terms/{term}/subjects/{subject}', 'SubjectTermController@show');
    Route::get('terms/{term}/subjects/{subject}/students', 'SubjectTermController@getStudents');
    Route::get('terms/{term}/subjects/{subject}/protors', 'SubjectTermController@getProtors');
    Route::get('terms/{term}/subjects/{subject}/quizs', 'SubjectTermController@getQuizs');
    Route::post('subject-term/terms/{term}/subjects/{subject}', 'SubjectTermController@store');
    Route::post('subject-term/subject', 'SubjectTermController@subjectsForTerm')->middleware('role:'.config('access.roles_list.protor'));
    Route::apiResource('terms', 'TermController');
});
