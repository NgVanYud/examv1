<?php
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 3/26/19
 * Time: 11:42 AM
 */
Route::group([
  'middleware' => [
    'assign.guard:student',
    'jwt.auth',
    'role:'.config('access.roles_list.student')
  ],
  'namespace' => 'API'
], function() {
    Route::get('quizs', 'QuizController@index');
    Route::get('quizs/{subjectTerm}', 'QuizController@show');
    Route::post('quizs/{subjectTerm}/result', 'QuizController@storeResult');
});
