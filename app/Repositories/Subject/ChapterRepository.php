<?php

namespace App\Repositories\Subject;

use App\Exceptions\GeneralException;
use App\Models\Chapter;
use App\Repositories\BaseRepository;

class ChapterRepository extends BaseRepository
{

  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return Chapter::class;
  }


  /**
   * Get the specified model record from the database.
   *
   * @param       $id
   * @param array $columns
   *
   * @return Collection|Model
   */
  public function getById($id, array $columns = ['*'])
  {
    $this->unsetClauses();

    $this->newQuery()->eagerLoad();

    return $this->query->find($id, $columns);
  }


  public function existed($id) {
    if($this->getById($id)) {
      return true;
    }
    throw new GeneralException(
      __('exceptions.invalid_data'),
      422
    );
  }
}
