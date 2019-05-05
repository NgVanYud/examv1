<?php

Route::group([
  'namespace' => 'API',
  'middleware' => 'jwt.auth',
], function () {
  Route::resource('formats', 'ExamFormatController');
});

