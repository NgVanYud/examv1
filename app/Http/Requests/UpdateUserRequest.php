<?php

namespace App\Http\Requests;

use App\Exceptions\GeneralException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        'active' => 'boolean',
        'email' => 'min:3|max:254|unique:users,email,'.$this->user->id,
        'username' => 'required|min:3|max:24|unique:users,username,'.$this->user->id,
        'code' => 'required|min:3|max:19|unique:users,code,'.$this->user->id,
        'first_name' => 'required|min:1|max:254',
        'last_name' => 'required|min:1|max:254',
        'role_ids' => 'required|array|min:1',
      ];
    }

  /**
   * Handle a failed validation attempt.
   *
   * @param \Illuminate\Contracts\Validation\Validator $validator
   * @return void
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  protected function failedValidation(Validator $validator)
  {
    throw new GeneralException('Dữ liệu không hợp lệ', 400);
  }


}
