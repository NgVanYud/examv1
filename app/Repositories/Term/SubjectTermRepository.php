<?php
//
//
namespace App\Repositories\Term;
//
//
use App\Exceptions\GeneralException;
use App\Models\SubjectTerm;
use App\Repositories\BaseRepository;
use App\Repositories\Quiz\QuizRepository;
use App\Repositories\Subject\SubjectRepository;
use App\Repositories\User\ProtorTermRepository;
use App\Repositories\User\StudentRepository;
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
  public $subjectRepository;
  public $studentRepository;

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
  public function __construct(UserRepository $userRepository,
                              QuizRepository $quizRepository,
                              TermRepository $termRepository,
                              SubjectRepository $subjectRepository,
                              StudentRepository $studentRepository,
                              ProtorTermRepository $protorTermRepository)
  {
    $this->userRepository = $userRepository;
    $this->quizRepository = $quizRepository;
    $this->termRepository = $termRepository;
    $this->protorTermRepository = $protorTermRepository;
    $this->subjectRepository = $subjectRepository;
    $this->studentRepository = $studentRepository;
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
      $subject = $this->subjectRepository->getById($subjectId);

      // Update subject_term table
      $subjectTerm = $this->where('term_id', $termId)
      ->where('subject_id', $subjectId)
      ->first();
      $subjectTerm->update(['original_exam_num' => $data['original_exam_num']]);

      $studentsData = $this->parseUserData(config('access.roles_list.student'), $students, $term->code, $subject->code);
      $protorsData = $data['protors'];
      // Store protors
      $this->storeProtorsForTerm($subjectTerm, $protorsData);
      // Store student
      $studentsList = $this->studentRepository->storeMulti($studentsData);
      // Create quiz
      $quizs = $this->quizRepository->createQuiz($subjectTerm->id, $termId, $subjectId, $data['original_exam_num']);
        //Assign quiz for each student
      $this->quizRepository->assignQuizs($quizs, $studentsList);
      return $studentsList;
    });
  }

  /**
   * @param $role (String)
   * @param $users
   * @param $termCode (String)
   * @return array
   */
  public function parseUserData($role, $users, $termCode = '', $subjectCode = '') {
    \Log::info('role ne: '.$role);
    $arr = [];
    foreach ($users as $tmpUser) {
      $parsedData = [];
      $parsedData['first_name'] = $tmpUser['Tên'];
      $parsedData['last_name'] = $tmpUser['Họ'];
      $parsedData['code'] = $tmpUser['Mã Số'];
      $parsedData['username'] = $this->setUsername($role, $tmpUser['Mã Số'], $termCode, $subjectCode);
      $parsedData['role_ids'] = [(Role::findByName($role, 'student'))->id];
      $arr[] = $parsedData;
    }
    return $arr;
  }

  public function setUsername($role, $code, $termCode = '', $subjectCode) {
    if($role == config('access.roles_list.student')) {
      return $termCode.'-'.$subjectCode.'-'.$code;
    }
    return $code;
  }

  /**
   * Assign protors to a specified term
   * @param SubjectTerm $subjectTerm
   * @param array $protorIds
   */
  public function storeProtorsForTerm($subjectTerm, $protorIds) {
//    $protorCounter = count($protorIds);
//    foreach ($protorIds as $protorId) {
//      if(!($this->protorTermRepository->where('protor_id', $protorId)
//        ->where('subject_term_id', $subjectTermId)
//        ->get()
//        ->count() > 0)) {
//        $this->protorTermRepository->create(['protor_id' => $protorId, 'subject_term_id' => $subjectTermId]);
//      }
//    }
    $subjectTerm->protors()->sync($protorIds);
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

  /**
   * Lấy mảng các SubjectTerm đang chờ để thi theo tài khoản người coi thi
   * @param $role
   * @param $user
   * @return array
   * @throws GeneralException
   */
  public function getSubjectIdsForTermByUser($role, $user) {
    if($user->hasRole($role)) {
      // danh sach subject_term ma nguoi dung trong thi
      $subjectTermOfUser = $user->subjectTerms;
      $subjectTerms = [];
      foreach ($subjectTermOfUser as $subjectTerm) {
        $tmpTerm = $this->termRepository->getById($subjectTerm->term_id);
        if($tmpTerm && $tmpTerm->active && !$tmpTerm->is_done) {
          $subjectTerms[] = $subjectTerm;
        }
      }
      return $subjectTerms;
    } else {
      throw new GeneralException('You do not have permission', 401);
    }
  }
}
