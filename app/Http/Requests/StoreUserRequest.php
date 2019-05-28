<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        'email' => 'min:3|max:254|unique:users,email',
        'username' => 'required|min:3|max:15|unique:users,username',
        'code' => 'required|min:3|max:15|unique:users,code',
        'first_name' => 'required|min:1|max:254',
        'last_name' => 'required|min:1|max:254',
        'role_ids' => 'required|array|min:1',
      ];
    }
}
