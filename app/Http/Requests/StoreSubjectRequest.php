<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
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
            'code' => 'required|max:10|min:5|unique:subjects,code',
            'name' => 'required|max:150|min:5',
            'credit' => 'required|numeric|min:1|max:10',
            'description' => 'nullable'
        ];
    }
}
