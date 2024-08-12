<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;

class CategoryRequest extends FormRequest
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
            'name'   => ['required','string','max:100','unique:categories,name'],
            'status' => ['required','in:1,2']
        ];

        if (request()->update_id) {
            $rules['name'][3] = 'unique:categories,name,'.request()->update_id;
        }

        return $rules;
    }
}