<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'              => 'required|exists:users,id',
            'name'            => "sometimes|required|min:3|max:200",
            'email'           => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($this->id)],
            'password'        => 'sometimes|required|min:6|max:100',
            'phone'           => ['sometimes', 'required', 'digits:11', Rule::unique('users', 'phone')->ignore($this->id)],
            'address'         => 'sometimes|required|min:3|max:100',
            'national_number' => 'sometimes|required',
            'bank_account'    => 'sometimes|required',
        ];
    }
}
