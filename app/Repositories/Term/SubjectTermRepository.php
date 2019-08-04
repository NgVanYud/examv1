<?php
//
//
namespace App\Repositories\Term;
//
//
use App\Exceptions\GeneralException;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\Student;
use App\Models\SubjectTerm;
use App\Repositories\BaseRepository;
use App\Repositories\Quiz\QuizRepository;
use App\Repositories\Result\ResultRepository;
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
  public $resultRepository;

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
                              ProtorTermRepository $protorTermRepository,
                              ResultRepository $resultRepository
  )
  {
    $this->userRepository = $userRepository;
    $this->quizRepository = $quizRepository;
    $this->termRepository = $termRepository;
    $this->protorTermRepository = $protorTermRepository;
    $this->subjectRepository = $subjectRepository;
    $this->studentRepository = $studentRepository;
    $this->resultRepository = $resultRepository;
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

  public function storeSetting($termId, $subjectId, $data)
  {
    return DB::transaction(function () use ($data, $termId, $subjectId) {
      $students = $data['students'];
      $protors = $data['protors'];
      $term = $this->termRepository->getById($termId);
      $subject = $this->subjectRepository->getById($subjectId);

      // Update subject_term table
      $subjectTerm = $this->where('term_id', $termId)
        ->where('subject_id', $subjectId)
        ->first();
      $subjectTerm->update([
        'original_exam_num' => $data['original_exam_num'],
        'is_configed' => SubjectTerm::CONFIGED_CODE
      ]);

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
  public function parseUserData($role, $users, $termCode = '', $subjectCode = '')
  {
    \Log::info('role ne: ' . $role);
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

  public function setUsername($role, $code, $termCode = '', $subjectCode)
  {
    if ($role == config('access.roles_list.student')) {
      return $termCode . '-' . $subjectCode . '-' . $code;
    }
    return $code;
  }

  /**
   * Assign protors to a specified term
   * @param SubjectTerm $subjectTerm
   * @param array $protorIds
   */
  public function storeProtorsForTerm($subjectTerm, $protorIds)
  {
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
  public function parseDataForExcel($data, $columns)
  {
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
  public function getSubjectIdsForTermByUser($role, $user)
  {
    if ($user->hasRole($role)) {
      // danh sach subject_term ma nguoi dung trong thi
      $subjectTermOfUser = $user->terms;
      $subjectTerms = [];
      foreach ($subjectTermOfUser as $subjectTerm) {
        $tmpTerm = $this->termRepository->getById($subjectTerm->term_id);
        if ($tmpTerm && $tmpTerm->is_actived && !$tmpTerm->is_done) {
          $subjectTerms[] = $subjectTerm;
        }
      }
      return $subjectTerms;
    } else {
      throw new GeneralException('You do not have permission', 401);
    }
  }

  /**
   * Kich hoat bai thi
   *
   * @param integer $subjectTermId
   */
  public function activeQuiz($subjectTermId)
  {
    $subjectTerm = $this->getById($subjectTermId);
    return DB::transaction(function () use ($subjectTerm) {
      // Mo bai thi
      $quizs = $subjectTerm->quizs()->update(['is_actived' => Quiz::ACTIVED_CODE]);
      // Kich hoat tai khoan sinh vien
      $students = $subjectTerm->students()->update(['is_actived' => Student::ACTIVED_CODE]);
      // Thay doi trang thai cua subjectTerm
      $subjectTerm->update(['status' => SubjectTerm::RUNNING]);
    });
  }

  /**
   * Đóng bài thi, tinh điểm.
   *
   * @param integer $subjectTermId
   */
  public function deactiveQuiz($subjectTermId)
  {
    $subjectTerm = $this->getById($subjectTermId);
    return DB::transaction(function () use ($subjectTerm) {
      /** Đóng bài thi **/
      $quizs = $subjectTerm->quizs()->update([
        'is_actived' => Quiz::DEACTIVED_CODE
      ]);
      /** Lưu kết qủa **/
      $allStudents = $subjectTerm->students;
      $this->storeResults($allStudents, $subjectTerm);
      /** Khóa môn thi **/
      $subjectTerm->update([
        'status' => SubjectTerm::CLOSED
      ]);
      /** Xóa tài khoản sinh viên **/
      $this->studentRepository->deleteMultipleById($allStudents->pluck('id')->all());
    });
  }

  /**
   * Luư kết quả bài làm của những sinh viên không dự thi vào bảng results
   */
  public function storeResults($students, $subjectTerm)
  {
    foreach ($students as $student) {
      $tmpResult = $this->resultRepository
        ->where('quiz_id', $student->quiz_id)
        ->where('subject_term_id', $subjectTerm->id)
        ->where('student_code', $student->code)
        ->get();
      if (count($tmpResult) === 0) {
        $tmpQuiz = $this->quizRepository->getById($student->quiz_id);
        $this->resultRepository->create([
          'student_code' => $student->code,
          'first_name' => $student->first_name,
          'last_name' => $student->last_name,
          'answer' => 0,
          'questions_total' => $tmpQuiz->question_num,
          'subject_term_id' => $subjectTerm->id,
          'quiz_id' => $student->quiz_id,
          'detail' => []
        ]);
      }
    }
  }

  public function getResults($subjectTermId) {
    $subjectTerm = $this->getById($subjectTermId);
    $results = $subjectTerm->results;
    return $results;
  }

  /**
   * Kich hoat is_actived = 1 trong bang subject_term de kich hoat
   * @param $subjectTermId
   */
  public function active($subjectTermId) {
    $subjectTerm = $this->getById($subjectTermId);
    return $this->updateById($subjectTermId, ['is_actived' => SubjectTerm::ACTIVED_CODE]);
  }

  /**
   * Khoa is_actived = 0 trong bang subject_term de kich hoat
   * @param $subjectTermId
   */
  public function deactive($subjectTermId) {
    $subjectTerm = $this->getById($subjectTermId);
    return $this->updateById($subjectTermId, ['is_actived' => SubjectTerm::DEACTIVED_CODE]);
  }
}
