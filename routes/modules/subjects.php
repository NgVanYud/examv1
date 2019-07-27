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
  Route::group([
    'middleware' => 'role:'.config('access.roles_list.exams_maker')
  ], function () {
      //Chapter
      Route::post('subjects/{subject}/chapters', 'SubjectController@storeChapter');
      Route::put('subjects/{subject}/chapters/{chapter}', 'SubjectController@updateChapter');
      Route::get('subjects/{subject}/chapters', 'SubjectController@getChapters');

      //Question
      Route::post('subjects/{subject}/questions', 'SubjectController@storeQuestion');
      Route::put('subjects/{subject}/questions/{question}', 'SubjectController@updateQuestion');
      Route::get('subjects/{subject}/questions', 'SubjectController@getQuestions');
      Route::post('subjects/{subject}/questions/{question}/deactive', 'QuestionController@deactive');
      Route::post('subjects/{subject}/questions/{question}/active', 'QuestionController@active');
      Route::get('subjects/{subject}/questions/{question}', 'QuestionController@show');

      //Format
      Route::post('subjects/{subject}/formats', 'ExamFormatController@store');
      Route::put('subjects/{subject}/formats/{format}', 'ExamFormatController@update');
//      Route::get('subjects/{subjectId}/formats', 'ExamFormatController@index');
      Route::get('subjects/{subject}/formats', 'SubjectController@getExamFormat');
      Route::get('subjects/{subjectId}/formats/{formatId}', 'ExamFormatController@show');
  });

  Route::group([
    'middleware' => 'role:'.config('access.roles_list.admin')
  ], function() {
    Route::get('subjects/{subject}/exam-makers', 'SubjectController@getExamMakers');
    Route::post('subjects/{subject}/exam-makers', 'SubjectController@storeExamMaker');
    Route::delete('subjects/{subject}/exam-makers', 'SubjectController@removeExamMaker');
    Route::post('subjects/delete-multi', 'SubjectController@destroyMulti');
    Route::resource('subjects', 'SubjectController')->only([ 'store', 'destroy', 'update' ]);
  });

  Route::get('subjects/{subjectId}/get-by-id', 'SubjectController@getById');
  Route::resource('subjects', 'SubjectController')->only([ 'index', 'show' ])->middleware(
    'role:'.config('access.roles_list.admin').'|'.
    config('access.roles_list.exams_maker').'|'.
    config('access.roles_list.curator'));

    Route::get('test', function() {
      $examFormat =  \App\Models\Format::find(2);
      return $examFormat;
      $format = $examFormat->format;
      $sum = 0;
      foreach ($format as $key => $value) {
        $sum += $value;
      }
      return $sum;
    });
});
