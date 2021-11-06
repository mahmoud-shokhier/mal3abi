<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            // 'role'     => ['required', Rule::in([User::Playground, User::User])],
            'name'     => ['required', 'min:3', 'max:200'],
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6', 'max:100'],
            'phone'    => ['required', 'digits:11', 'unique:users'],
            'avatar'   => ['nullable', 'image', 'max:2000']
        ];
    }
}
