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
use App\Models\Option;
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
   */
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
          'chapter_id' => $data['chapter_id']
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
          'content' => $data['content']
        ];
        $question = $this->questionRepository->updateById($questionId, $questionData);
        $options = $question->options;
        //Check if the id of answer exists into the options array
        if($options->contains('id', $data['answer'])) {
          $optionsData = $data['options'];
          foreach ($options as $key => $option) {
            $option->update([
              'content' => $optionsData[$key],
              'question_id' => $questionId,
              'is_correct' => $option->id == $data['answer'] ? Option::CODE_CORRECT : Option::CODE_INCORRECT
            ]);
          }
          return $question;
        } else {
          throw new GeneralException(
            __('exceptions.invalid_data'),
            422
          );
        }

      });
      throw new GeneralException(
        __('exceptions.general'),
        422
      );
    }
}
