<?php

namespace App\Http\Controllers\API;

use App\Exceptions\GeneralException;
use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateChapterRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\Chapter\ChapterResource;
use App\Http\Resources\Question\QuestionCollection;
use App\Http\Resources\Question\QuestionResource;
use App\Http\Resources\Subject\SubjectCollection;
use App\Http\Resources\Subject\SubjectResource;
use App\Http\Resources\User\UserResource;
use App\Models\Subject;
use App\Repositories\Subject\ChapterRepository;
use App\Repositories\Subject\SubjectRepository;
use App\Repositories\User\ManagerRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public $subjectRepository;

    public $chapterRepository;

    public $userRepository;

    public $managerRepository;

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
    public function __construct(
      SubjectRepository $subjectRepository,
      ChapterRepository $chapterRepository,
      UserRepository $userRepository,
      ManagerRepository $managerRepository){
        $this->subjectRepository = $subjectRepository;
        $this->chapterRepository = $chapterRepository;
        $this->userRepository = $userRepository;
        $this->managerRepository = $managerRepository;
        $this->authorizeResource(Subject::class, 'subject');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $user = Auth::user();
      $conditions = [
        'orderBy' => ($request->order_by ? $request->order_by : 'code'),
        'order' => ($request->order && in_array($request->order, [ 'desc', 'asc' ]) ? $request->order : 'asc'),
        'perPage' => ($request->limit && intval($request->limit) > 0 ? $request->limit: 10),
      ];
      $subjects = new Collection();
      $keyword = $request->keyword;
      if($user->hasRole(config('access.roles_list.admin'))) {
        $subjects = $this->subjectRepository
          ->where('code',"%{$keyword}%", 'like')
          ->orWhere('name', 'like', "%{$keyword}%")
          ->orWhere('credit', 'like', "%{$keyword}%")
          ->orWhere('description', 'like', "%{$keyword}%")
          ->orderBy($conditions['orderBy'], $conditions['order'])
          ->paginate($conditions['perPage']);
      } else if($user->hasRole(config('access.roles_list.exams_maker'))) {
//        return $user->subjects;
        $subjects = $user->subjects()
//          ->where('exam_maker_id', $user->id)
//          ->where('code',"%{$keyword}%", 'like')
//          ->orWhere('name', 'like', "%{$keyword}%")
//          ->orWhere('credit', 'like', "%{$keyword}%")
//          ->orWhere('description', 'like', "%{$keyword}%")
          ->orderBy($conditions['orderBy'], $conditions['order'])
          ->paginate($conditions['perPage']);
      } else if ($user->hasRole(config('access.roles_list.curator'))) {
        $subjects = $this->subjectRepository->get();
      }
      return new SubjectCollection($subjects);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubjectRequest $request)
    {
        return $this->subjectRepository->create($request->only(
            'code', 'name', 'credit', 'description'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $subject)
    {
        return new SubjectResource($subject);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubjectRequest $request, $subject)
    {
        return $this->subjectRepository->updateById($subject->id, $request->only([
            'code', 'name', 'credit', 'description'
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $subject)
    {
        $this->subjectRepository->deleteBySlug($subject->slug);
    }

    public function destroyMulti(Request $request) {
      $slugs = $request->items;

      $this->subjectRepository->deleteMultipleBySlug($slugs);
    }

    public function storeChapter(StoreChapterRequest $request, $subjectId) {
      if($this->subjectRepository->existed($subjectId)) {
        $chapterData = $request->all() + ['subject_id' => (int)$subjectId];
        return $this->chapterRepository->create($chapterData);
      }
      throw new GeneralException(
        __('exceptions.invalid_data'),
        422
      );
    }

    public function updateChapter(UpdateChapterRequest $request, $subjectId, $chapterId) {
      if($this->chapterRepository->existed($chapterId)) {
        return $this->chapterRepository->updateById($chapterId, $request->all());
      }
      throw new GeneralException(
        __('exceptions.invalid_data'),
        422
      );
    }

    public function getChapters($subjectId) {
      return ChapterResource::collection($this->subjectRepository->getChapters($subjectId));
    }

    public function storeQuestion(StoreQuestionRequest $request, $subjectId, $chapterId) {
      if($this->subjectRepository->containChapter($subjectId, $chapterId)) {
        return $this->subjectRepository->storeQuestion($request->all());
      }
      throw new GeneralException(
        __('exceptions.invalid_data'),
        422
      );
    }

    public function updateQuestion(UpdateQuestionRequest $request, $subjectId, $chapterId, $questionId) {
      if($this->chapterRepository->containQuestion($chapterId, $questionId)) {
        return $this->subjectRepository->updateQuestion($questionId, $request->all());
      }
      throw new GeneralException(
        __('exceptions.invalid_data'),
        422
      );
    }

    public function getQuestions(Request $request, $subjectId) {
      return QuestionResource::collection($this->subjectRepository->getQuestions($subjectId, $request->all()));
    }

    public function getExamMakers(Request $request, $subject) {
      $examMkers = $subject->examMakers;
      return UserResource::collection($examMkers);
    }

    public function storeExamMaker(Request $request, $subject) {
      $examMakerUuid = $request->user_uuid;
      $examMaker = $this->managerRepository->getByUuid($examMakerUuid);
      return $examMaker->quizMakeSubjects()->attach($subject->id);
    }

    public function removeExamMaker(Request $request, $subject) {
      $examMakerUuid = $request->user_uuid;
      $examMaker = $this->managerRepository->getByUuid($examMakerUuid);
      return $examMaker->quizMakeSubjects()->detach($subject->id);
    }

    public function getExamFormat(Request $request, $subjectId) {
      $subject = $this->subjectRepository->getById($subjectId);
      return $subject->format;
    }

    public function getById(Request $request, $subjectId) {
      return new SubjectResource($this->subjectRepository->getById($subjectId));
    }
}
