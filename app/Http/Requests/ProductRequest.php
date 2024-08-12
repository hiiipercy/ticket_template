<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductRequest extends FormRequest
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
        $rules = [
            'category_id'   => ['required'],
            'name'          => ['required','string','max:100'],
            'status'        => ['required','in:1,2']
        ];

        return $rules;
    }
}