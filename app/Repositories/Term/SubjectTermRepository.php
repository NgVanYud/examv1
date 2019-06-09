<?php
//
//
namespace App\Repositories\Term;
//
//
use App\Models\SubjectTerm;
use App\Repositories\BaseRepository;
use App\Repositories\Quiz\QuizRepository;
use App\Repositories\User\ProtorTermRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Term\TermRepository;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
//
class SubjectTermRepository extends BaseRepository
{

  public $userRepository;
  public $quizRepository;
  public $termRepository;
  public $protorTermRepository;

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
  public function __construct(UserRepository $userRepository, QuizRepository $quizRepository, TermRepository $termRepository, ProtorTermRepository $protorTermRepository)
  {
    $this->userRepository = $userRepository;
    $this->quizRepository = $quizRepository;
    $this->termRepository = $termRepository;
    $this->protorTermRepository = $protorTermRepository;
    $this->makeModel();
  }


  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return SubjectTerm::class;
  }

  public function storeSetting($termId, $subjectId, $data) {
    return DB::transaction(function() use ($data, $termId, $subjectId) {
      $students = $data['students'];
      $protors = $data['protors'];
      $term = $this->termRepository->getById($termId);

      // Update subject_term table
      $subjectTerm = $this->where('term_id', $termId)
      ->where('subject_id', $subjectId)
      ->first();
      $subjectTerm->update(['original_exam_num' => $data['original_exam_num']]);

      // Store students
      $studentsData = $this->parseUserData(config('access.roles_list.student'), $students, $term->code);

      $protorsData = $data['protors'];
      // Store protors
      $this->storeProtorsForTerm($subjectTerm->id, $protorsData);
      // Store student
      $studentsList = $this->userRepository->storeMulti($studentsData, true);
      // Create quiz
      $quizs = $this->quizRepository->createQuiz($subjectTerm->id, $termId, $subjectId, $data['original_exam_num']);
        //Assign quiz for each student
      $this->quizRepository->assignQuizs($quizs, $studentsList);
//      $subStudentsData = $this->parseDataForExcel($studentsList, [
//        'code' => 'Mã Số',
//        'last_name' => 'Họ',
//        'first_name' => 'Tên',
//        'username' => 'Tài Khoản',
//        'plain_pwd' => 'Mật Khẩu',
//      ]);
//      $subStudentsData = $studentsList->map(function ($user) {
//        return collect($user->toArray())
//          ->only(['last_name', 'first_name', 'code', 'username', 'plain_pwd'])
//          ->all();
//      });
      return $studentsList;
//      $this->userRepository->storeMulti();
//      return $subjectTerm;
    });
  }

  /**
   * @param $role (String)
   * @param $users
   * @param $termCode (String)
   * @return array
   */
  public function parseUserData($role, $users, $termCode = '') {
    $counter = count($users);
    $arr = [];
    for ($i = 0; $i < $counter; $i++) {
      $tmpUser = $users[$i];
      $parsedData = [];
      $parsedData['first_name'] = $tmpUser['Tên'];
      $parsedData['last_name'] = $tmpUser['Họ'];
      $parsedData['code'] = $tmpUser['Mã Số'];
      $parsedData['username'] = $this->setUsername($role, $tmpUser['Mã Số'], $termCode);
      $parsedData['role_ids'] = [(Role::findByName($role))->id];
      $arr[] = $parsedData;
    }
    return $arr;
  }

  public function setUsername($role, $code, $termCode = '') {
    if($role == config('access.roles_list.student')) {
      return $termCode.'-'.$code;
    }
    return $code;
  }

  /**
   * Assign protors to a specified term
   * @param $subjectTermId
   * @param $protorIds
   */
  public function storeProtorsForTerm($subjectTermId, $protorIds) {
    $protorCounter = count($protorIds);
    for($i = 0; $i < $protorCounter; $i++) {
      if(!($this->protorTermRepository->where('protor_id', $protorIds[$i])
        ->where('subject_term_id', $subjectTermId)
        ->get()
        ->count() > 0)) {
        $this->protorTermRepository->create(['protor_id' => $protorIds[$i], 'subject_term_id' => $subjectTermId]);
      }
    }
  }

  /**
   * Create data for excel exportation
   * @param $data
   * @param $columns (Array: ['actual_key' => 'column_name'])
   */
  public function parseDataForExcel($data, $columns) {
    $dataCounter = count($data);
    $newData = [];
    for ($i = 0; $i < $dataCounter; $i++) {
      $tmp = ($data[$i])->toArray();
      $currentItem = [];
      foreach ($columns as $actualKey => $colName) {
        $currentItem[$colName] = $tmp[$actualKey];
      }
      $newData[] = $currentItem;
    }
    return $newData;
  }
}
