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
    Route::post('subjects/{subjectId}/chapters', 'SubjectController@storeChapter');
    Route::post('subjects/{subjectId}/chapters/{chapterId}/update', 'SubjectController@updateChapter');
    Route::get('subjects/{subjectId}/chapters', 'SubjectController@getChapters');

    Route::post('subjects/{subjectId}/chapters/{chapterId}/questions', 'SubjectController@storeQuestion');
    Route::post('subjects/{subjectId}/chapters/{chapterId}/questions/{questionId}/update', 'SubjectController@updateQuestion');

    Route::resource('subjects', 'SubjectController');
});
