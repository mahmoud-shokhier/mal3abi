<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CowpayWebhook extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'merchant_reference_id'        => 'required',
            'payment_gateway_reference_id' => 'required',
            'cowpay_reference_id'          => 'required',
            'order_status'                 => 'required',
            'signature'                    => 'required' ,
        ];
    }
}
