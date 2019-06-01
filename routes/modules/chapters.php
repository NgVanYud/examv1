<?php

Route::group([
  'namespace' => 'API',
  'middleware' => 'role:'.config('access.roles_list.exams_maker')
], function() {
  Route::apiResource('chapters', 'ChapterController');
});
