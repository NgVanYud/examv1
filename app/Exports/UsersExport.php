<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection
{
  public $data;
  /**
   * PHP 5 allows developers to declare constructor methods for classes.
   * Classes which have a constructor method call this method on each newly-created object,
   * so it is suitable for any initialization that the object may need before it is used.
   *
   * Note: Parent constructors are not called implicitly if the child class defines a constructor.
   * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
   *
   * param [ mixed $args [, $... ]]
   * @link https://php.net/manual/en/language.oop5.decon.php
   */
  public function __construct($data)
  {
    $this->data = $data;
  }

  /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
//        return new Collection($this->data);
        return User::all();
    }

//  /**
//   * @return array
//   */
//  public function headings(): array
//  {
//    return [
//
//    ];
//  }


}
