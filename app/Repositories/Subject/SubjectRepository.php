<?php
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 3/26/19
 * Time: 11:31 AM
 */

namespace App\Repositories\Subject;


use App\Exceptions\GeneralException;
use App\Http\Requests\StoreChapterRequest;
use App\Http\Resources\Question\QuestionCollection;
use App\Models\Option;
use App\Models\Question;
use App\Models\Subject;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class SubjectRepository extends BaseRepository
{

  public $chapterRepository;
  public $questionRepository;
  public $optionRepository;

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
   *
   */

  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return Subject::class;
  }

  public function __construct(
    ChapterRepository $chapterRepository,
    QuestionRepository $questionRepository,
    OptionRepository $optionRepository){
    $this->makeModel();
    $this->chapterRepository = $chapterRepository;
    $this->questionRepository = $questionRepository;
    $this->optionRepository = $optionRepository;
  }

  /**
   * Get the specified model record form the database by slug
   */
    public function getBySlug($slug, array $columns = ['*']) {
      $this->unsetClauses();

      $this->newQuery()->eagerLoad();

      return $this->where('slug', $slug)->first($columns);
    }

    public function deleteBySlug($slug) {
      return $this->getBySlug($slug)->delete();
    }

  /**
   * Delete multi records by an array of slugs
   */
    public function deleteMultipleBySlug($slugs) {
      DB::transaction(function () use ($slugs) {
        foreach ($slugs as $slug) {
          $this->deleteBySlug($slug);
        }
      }, 3);
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

    public function getChapters($subjectId) {
      $subject = $this->getById($subjectId);
      if($subject) {
        return $subject->chapters;
      }
      throw new GeneralException(
        __('exceptions.invalid_data'),
        422
      );
    }

    public function containChapter($subjectId, $chapterId) {
      if($this->existed($subjectId)) {
        if($this->chapterRepository->existed($chapterId)) {
          $chapter = $this->chapterRepository->getById($chapterId);
          if($chapter->subject->id == $subjectId) {
            return true;
          }
        }
      }
      return false;
    }

    public function storeQuestion($data) {
      return DB::transaction( function () use ($data){
        $question = $this->questionRepository->create([
          'content' => $data['content'],
          'subject_id' => $data['subject_id'],
          'chapter_id' => $data['chapter_id'],
          'is_actived' => $data['is_actived'] ? Question::ACTIVE_CODE : Question::INACTIVE_CODE,
        ]);
        $options = $data['options'];
        $optionCounter = count($options);
        for($i = 0; $i < $optionCounter; $i++) {
          $this->optionRepository->create([
            'content' => $options[$i],
            'question_id' => $question->id,
            'is_correct' => $i == (int)($data['answer']) ? Option::CODE_CORRECT : Option::CODE_INCORRECT
          ]);
        }
        return $question;

      });
      throw new GeneralException(
        __('exceptions.general'),
        422
      );
    }

    public function updateQuestion($questionId, $data) {
      return DB::transaction( function () use ($questionId, $data) {
        $questionData = [
          'content' => $data['content'],
          'is_actived' => $data['is_actived'],
        ];
        $question = $this->questionRepository->updateById($questionId, $questionData);
        $options = $question->options;
        //Check if the id of answer exists into the options array
        $optionsData = $data['options'];
        foreach ($options as $key => $option) {
          $option->update([
            'content' => $optionsData[$key],
            'question_id' => $questionId,
            'is_correct' => $key == $data['answer'] ? Option::CODE_CORRECT : Option::CODE_INCORRECT
          ]);
        }
        return $question;
      });
      throw new GeneralException(
        __('exceptions.general'),
        422
      );
    }

    public function getQuestions($subjectId, $conditions) {
      $chapterId = isset($conditions['chapter']) ? $conditions['chapter'] : '';
      $orderBy = $conditions['order_by'] ? $conditions['order_by'] : 'updated_at';
      $order = $conditions['order'] ? $conditions['order'] : 'aes';
      $perPage = $conditions['per_page'] ? $conditions['per_page'] : 10;
      if($chapterId) {
        return $this->questionRepository
          ->where('subject_id', $subjectId)
          ->where('chapter_id', $chapterId)
          ->orderBy($orderBy, $order)
          ->paginate($perPage);
      } else {
        return $this->questionRepository
          ->where('subject_id', $subjectId)
          ->orderBy($orderBy, $order)
          ->paginate($perPage);
      }
      throw new GeneralException(
        __('exceptions.invalid_data'),
        422
      );
    }

  /**
   * Check if the subject contains a question by Id
   *
   * @param $chapterId
   * @param $questionId
   * @return bool
   */
  public function containQuestion($subjectId, $questionId) {
    $question = $this->questionRepository->getById($questionId);
    if($question->subject->id == $subjectId) {
      return true;
    }
    return false;
  }
}
