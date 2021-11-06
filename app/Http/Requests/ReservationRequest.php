<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
            'day'   => 'required|date|after_or_equal:' . date("d-m-Y"),
            'start' => 'required|date_format:H:i',
            'end'   => 'required|date_format:H:i',
            'notes' => 'sometimes|required'
        ];
    }
}
