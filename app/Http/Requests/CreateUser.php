<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateUser extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role'     => ['required', Rule::in(User::All)],
            'name'     => "required|min:3|max:200",
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|max:100',
            'phone'    => 'required|digits:11|unique:users,phone',
        ];
    }
}
