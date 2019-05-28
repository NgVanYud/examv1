<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTermRequest extends FormRequest
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
            'name' => 'required|min:5|max:200|unique:terms,name,'.$this->term->id,
//            'begin' => 'required|date|after:tomorrow',
            'begin' => 'required|date_format:Y-m-d',
//            'end' => 'required|date|after:begin',
            'end' => 'required|date_format:Y-m-d|after:begin',
            'code' => 'required|min:3|max:15|unique:terms,code,'.$this->term->id,
            'active' => 'boolean',
            'subjects' => ''
        ];
    }
}
