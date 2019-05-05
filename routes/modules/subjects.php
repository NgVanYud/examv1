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
    //Chapter
    Route::post('subjects/{subjectId}/chapters', 'SubjectController@storeChapter');
    Route::post('subjects/{subjectId}/chapters/{chapterId}/update', 'SubjectController@updateChapter');
    Route::get('subjects/{subjectId}/chapters', 'SubjectController@getChapters');

    //Question
    Route::post('subjects/{subjectId}/chapters/{chapterId}/questions', 'SubjectController@storeQuestion');
    Route::post('subjects/{subjectId}/chapters/{chapterId}/questions/{questionId}/update', 'SubjectController@updateQuestion');
    Route::get('subjects/{subjectId}/questions', 'SubjectController@getQuestions');

    //Format
    Route::post('subjects/{subjectId}/formats', 'ExamFormatController@store');
    Route::post('subjects/{subjectId}/formats/{formatId}/update', 'ExamFormatController@update');
    Route::get('subjects/{subjectId}/formats', 'ExamFormatController@index');
    Route::get('subjects/{subjectId}/formats/{formatId}', 'ExamFormatController@show');

    Route::resource('subjects', 'SubjectController');

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
