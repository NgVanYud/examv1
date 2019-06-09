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
      Route::post('subjects/{subjectId}/chapters', 'SubjectController@storeChapter');
      Route::post('subjects/{subjectId}/chapters/{chapterId}/update', 'SubjectController@updateChapter');
      Route::get('subjects/{subjectId}/chapters', 'SubjectController@getChapters');

      //Question
      Route::post('subjects/{subjectId}/chapters/{chapterId}/questions', 'SubjectController@storeQuestion');
      Route::post('subjects/{subjectId}/chapters/{chapterId}/questions/{questionId}/update', 'SubjectController@updateQuestion');
      Route::get('subjects/{subjectId}/questions', 'SubjectController@getQuestions');
      Route::post('subjects/{subjectId}/questions/{questionId}/deactive', 'QuestionController@deactive');
      Route::post('subjects/{subjectId}/questions/{questionId}/active', 'QuestionController@active');
      Route::get('subjects/{subjectId}/questions/{questionId}/show', 'QuestionController@show');

      //Format
      Route::post('subjects/{subjectId}/formats', 'ExamFormatController@store');
      Route::post('subjects/{subjectId}/formats/{formatId}/update', 'ExamFormatController@update');
      Route::get('subjects/{subjectId}/formats', 'ExamFormatController@index');
      Route::get('subjects/{subjectId}/format', 'SubjectController@getExamFormat');
      Route::get('subjects/{subjectId}/formats/{formatId}', 'ExamFormatController@show');
  });

  Route::group([
    'middleware' => 'role:'.config('access.roles_list.admin')
  ], function() {
    Route::get('subjects/{id}/exam-makers', 'SubjectController@getExamMakers');
    Route::post('subjects/{id}/add-exam-maker', 'SubjectController@storeExamMaker');
    Route::post('subjects/{id}/remove-exam-maker', 'SubjectController@removeExamMaker');
    Route::post('subjects/delete-multi', 'SubjectController@destroyMulti');
    Route::resource('subjects', 'SubjectController')->only([ 'store', 'destroy', 'update' ]);
  });

  Route::get('subjects/{subjectId}/get-by-id', 'SubjectController@getById');
  Route::resource('subjects', 'SubjectController')->only([ 'index', 'show' ])->middleware('role:'.config('access.roles_list.admin').'|'.config('access.roles_list.exams_maker'));

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
