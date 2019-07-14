<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateManagerRequest extends FormRequest
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
      $user = $this->manager;
      return [
        'email' => 'min:3|max:254|unique:managers,email,'.$user->id,
        'username' => 'required|min:3|max:15|unique:managers,username,'.$user->id,
        'code' => 'required|min:3|max:15|unique:managers,code,'.$user->id,
        'first_name' => 'required|min:1|max:254',
        'last_name' => 'required|min:1|max:254',
        'role_ids' => 'required|array|min:1',
      ];
    }
}
