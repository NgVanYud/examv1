<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChapterRequest extends FormRequest
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
//        'name' => 'required|min:3|max:250|unique:chapters,name,'.$chapterId,
        'name' => [
          'required',
          'min:3',
          'max:250',
          Rule::unique('chapters')->where(function ($query) {
            return $query->where('subject_id', $this->subjectId)
              ->where('id', '!=', $this->chapterId)
              ->where('name', $this->name);
          })
        ],
      ];
    }
}
