<?php
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 3/26/19
 * Time: 11:31 AM
 */

namespace App\Repositories\Subject;


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

}
