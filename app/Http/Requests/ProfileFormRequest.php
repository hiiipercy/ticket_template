<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileFormRequest extends FormRequest
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
            'name'      => 'required|string',
            'email'     => 'required|string|email',
            'mobile_no' => 'nullable|string|max:15|unique:users,mobile_no,'.Auth::id(),
            'gender'    => 'required|in:1,2',
            'image'     => 'nullable|image|mimes:jpg,png,jpeg',
        ];
    }
}
