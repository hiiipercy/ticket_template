<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => 'required|email|unique:users,email',
            // 'phone_no'  => 'required|numeric|bdPhone|unique:users,phone_no',
            'mobile_no'  => 'nullable|numeric|regex:/^01[3-9]\d{8}$/|unique:users,mobile_no',
            // 'role_type' => ['required','in:Admin,User'],
            'role_type'    => ['required','in:1,2'],
            'password'  => 'required',
            'status'    => ['required','in:1,2'],
            'image'     => ['nullable','image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];

        return $rules;
    }
    // public function messages()
    // {
    //     return [
    //         'email.required'=>'The email field is required.',
    //     ];
    // }
}
