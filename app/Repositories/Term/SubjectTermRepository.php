<?php


namespace App\Repositories\Term;


use App\Models\SubjectTerm;
use App\Repositories\BaseRepository;

class SubjectTermRepository extends BaseRepository
{
  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return SubjectTerm::class;
  }
}
