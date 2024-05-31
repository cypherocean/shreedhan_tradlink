<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriberRequest extends FormRequest
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
        if($this->method() == 'PATCH'){
            return [
                'name' => 'required',
                'number' => 'required|numeric|unique:subscribers,number,'.$this->id,
                'carrier' => 'required'
            ];
        }else{
            return [
                'name' => 'required',
                'number' => 'required|numeric|unique:subscribers,number',
                'carrier' => 'required',
            ];
        }
    }

    public function messages(){
        return [
            'name.required' => 'Please enter name!',
            'number.required' => 'Please enter phone number!',
            'number.numeric' => 'Please enter valid phone number!',
            'number.unique' => 'This number is already registered!',
            'carrier.required' => 'Please select network carrier!',
        ];
    }
}
