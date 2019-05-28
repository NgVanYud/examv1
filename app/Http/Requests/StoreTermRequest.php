<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTermRequest extends FormRequest
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
            'name' => 'required|min:5|max:200|unique:terms,name',
//            'begin' => 'required|date|after:tomorrow',
            'begin' => 'required|date_format:Y-m-d',
//            'end' => 'required|date|after:begin',
            'end' => 'required|date_format:Y-m-d',
            'code' => 'required|min:3|max:15|unique:terms,code',
            'active' => 'boolean'
        ];
    }
}
