<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      return [
        'chapter_id' => 'required',
        'subject_id' => 'required',
        'content' => [
          'required',
          'min:5',
          Rule::unique('questions')->where(function ($query) {
            return $query->where('chapter_id', request('chapter_id'))
              ->where('id', '!=', $this->question->id)
              ->where('content', request('content'));
          }),
        ],
        'options.*' => 'required|min:1',
        'answer'    => 'required|min:1',
      ];
    }
}
