<?php

Route::group([
  'middleware' => ['jwt.auth'],
  'namespace' => 'API'
], function () {
  Route::post('files/upload', 'FileController@store');
});
