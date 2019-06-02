<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(Request $request) {
      $path = $this->save($request->file('upload'), 'images');
      return Storage::url($path);
    }

  /**
   * Store the path of the file that is saved recently
   * @param $file
   * @param $path
   * @param null $name
   * @return mixed
   */
    public function save($file, $path, $name = null) {
      if(!$name) {
        $realPath = Storage::putFile($path, $file);
      } else {
        $realPath = Storage::putFileAs($path, $file, $name);
      }
      return $realPath;
    }
}
