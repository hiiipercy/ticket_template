<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;

class MenuRequest extends FormRequest
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
            'title'           => 'required|string|max:255',
            'filter_name'     => 'required|string|max:255',
            'price'           => 'required|string|max:255',
            'description'     => 'required|string',
        ];
    }
}
