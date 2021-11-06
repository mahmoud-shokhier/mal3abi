<?php

namespace App\Http\Requests;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /** @var User $user */
        $user = \auth()->user();
        return [
            'name'            => ['required', 'min:3', 'max:200'],
            'email'           => ['sometimes', 'required', 'email', 'unique:users,email,' . $user->id],
            'password'        => ['sometimes', 'required', 'min:6', 'max:100'],
            'phone'           => ['sometimes', 'required', 'digits:11', 'unique:users,phone,' . $user->id],
            'national_number' => ['sometimes', 'required'],
            'bank_account'    => ['sometimes', 'required'],
            'avatar'          => ['sometimes', 'required', 'image', 'mimes:jpeg,jpg,png', 'max:2000'],
        ];
    }
}
