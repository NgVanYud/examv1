<?php

namespace App\Console\Commands;

use App\Models\Option;
use App\Repositories\Subject\QuestionRepository;
use Illuminate\Console\Command;

class EditOptionsQuestions extends Command
{
    protected $questionRepository;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'questions:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edit options in questions that is invalid';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(QuestionRepository $questionRepository)
    {
        parent::__construct();
        $this->questionRepository = $questionRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '1024M');
        $all_questions = $this->questionRepository->orderBy('created_at', 'asc')->get();
        $all_questions->each(function($question, $key) {
            $all_options = $question->options;
            $check_arr = $all_options->pluck('is_correct')->toArray();
            /*
             * Sai toàn bộ
             */
            if(in_array('0', $check_arr) && !in_array('1', $check_arr)) {
              $randomIndex = rand(0, 3);
              $tmpOption = $all_options[$randomIndex];
              $tmpOption->update(['is_correct' => Option::CODE_CORRECT]);
            }
        });
        $this->info("Update questions successfull.");
    }
}
