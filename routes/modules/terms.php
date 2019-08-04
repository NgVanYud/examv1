<?php
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 3/26/19
 * Time: 11:42 AM
 */
Route::group([
  'middleware' => ['jwt.auth', 'role:' . config('access.roles_list.curator') . '|' . config('access.roles_list.protor')],
  'namespace' => 'API'
], function () {
  Route::get('terms/{term}/subjects/{subject}', 'SubjectTermController@show');
  Route::get('terms/{term}/subjects/{subject}/students', 'SubjectTermController@getStudents');
  Route::get('terms/{term}/subjects/{subject}/protors', 'SubjectTermController@getProtors');
  Route::get('terms/{term}/subjects/{subject}/quizs', 'SubjectTermController@getQuizs');
  Route::post('subject-term/terms/{term}/subjects/{subject}', 'SubjectTermController@store');
  Route::get('subject-term/subjects', 'SubjectTermController@subjectsForTerm')
    ->middleware('role:' . config('access.roles_list.protor'));
  Route::post('subject-term/subject/active-quiz', 'SubjectTermController@activeQuiz')
    ->middleware('role:' . config('access.roles_list.protor'));
  Route::post('subject-term/subject/deactive-quiz', 'SubjectTermController@deactiveQuiz')
    ->middleware('role:' . config('access.roles_list.protor'));
  Route::post('subject-term/active', 'SubjectTermController@active')
    ->middleware('role:' . config('access.roles_list.curator'));
  Route::post('subject-term/deactive', 'SubjectTermController@deactive')
    ->middleware('role:' . config('access.roles_list.curator'));
  Route::get('subject-term/{subjectTerm}/subject/results', 'SubjectTermController@getResults')
    ->middleware('role:' . config('access.roles_list.protor'). '|' . config('access.roles_list.curator'));
  Route::get('subject-term/quiz', 'SubjectTermController@getQuiz')
    ->middleware('role:' . config('access.roles_list.student'));
  Route::apiResource('terms', 'TermController');
});
