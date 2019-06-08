<?php

use Illuminate\Database\Seeder;

class SubjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Subject::class, 10)->create()
            ->each(function($subject) {
                $subject->chapters()->saveMany(factory(\App\Models\Chapter::class, 10)
                    ->create(['subject_id' => $subject->id])
                    ->each(function($chapter) {
                        $chapter->questions()->saveMany(factory(\App\Models\Question::class, 150)
                        ->create(['subject_id' => $chapter->subject_id, 'chapter_id' => $chapter->id])
                        ->each(function ($question) {
                            $question->options()->saveMany(factory(\App\Models\Option::class, 4)
                            ->create(['question_id' => $question->id]));
                }));
            }));
        });

    }
}
