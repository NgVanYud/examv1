<?php

Route::group([
  'namespace' => 'API',
  'middleware' => 'jwt.auth',
], function () {
  Route::resource('questions', 'QuestionController');
});
