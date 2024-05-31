<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RechargeRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required',
            'amount' => 'required|numeric',
            'validity' => 'required|numeric',
            'carrier' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Please select user',
            'user_id.exists' => 'Please select valid user',
            'amount.required' => 'Please enter amount!',
            'amount.numeric' => 'Please enter valid amount!',
            'validity.required' => 'Please enter validity!',
            'validity.numeric' => 'Please enter valid validity!',
            'carrier.required' => 'Please select network carrier!',
        ];
    }
}
