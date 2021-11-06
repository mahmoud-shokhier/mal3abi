<?php

namespace App\Http\Requests;

use App\Playground;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlaygroundRequest extends FormRequest
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
            'name'        => ['required'],
            'address'     => ['required'],
            'lat'         => ['required'],
            'long'        => ['required'],
            'price_day'   => ['required', 'regex:/^\d*(\.\d{1,2})?$/'],
            'price_night' => ['required', 'regex:/^\d*(\.\d{1,2})?$/'],
            'day_time'    => ['required', 'date_format:H:i'],
            'night_time'  => ['required', 'date_format:H:i'],
            'status'      => [Rule::in([Playground::STATUS_OPEN, Playground::STATUS_CLOSE])],
            'images'      => 'array',
            'images.*'    => 'image|mimes:jpeg,jpg,png|max:2000'
        ];
    }
}
