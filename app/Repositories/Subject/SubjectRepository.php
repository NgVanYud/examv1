<?php
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 3/26/19
 * Time: 11:31 AM
 */

namespace App\Repositories\Subject;


use App\Http\Requests\StoreChapterRequest;
use App\Models\Subject;
use App\Repositories\BaseRepository;

class SubjectRepository extends BaseRepository
{
    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return Subject::class;
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
      return false;
    }
}
