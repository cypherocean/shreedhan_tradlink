<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'password' => 'nullable|string|min:8',
            'c_password' => 'required_with:password|same:password',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter Name!',
            'email.required' => 'Please enter Email Address!',
            'email.email' => 'Please enter a valid Email Address!',
            'password.min' => 'Password should be at least 8 characters long!',
            'c_password.required_with' => 'The confirm password is required when the password is present.',
            'c_password.same' => 'The confirm password does not match the password.',
        ];
    }
}
