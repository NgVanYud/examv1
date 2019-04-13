<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubjectRequest extends FormRequest
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
            'code' => 'required|max:10|min:5|unique:subjects,code,'.$this->subject->id,
            'name' => 'required|max:150|min:5|unique:subjects,name,'.$this->subject->id,
            'credit' => 'required|numeric|min:1|max:10',
            'description' => 'nullable'
        ];
    }
}
