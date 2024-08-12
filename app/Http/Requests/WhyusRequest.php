<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Auth;

class WhyusRequest extends FormRequest
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
            'title'=>'required|string|max:255',
            'subtitle'=>'required|string|max:255',
            'list_1'=>'required|string',
            'list_title_1'=>'required|string|max:255',
            'list_description_1'=>'required|string|max:255',
            'list_2'=>'required|string',
            'list_title_2'=>'required|string|max:255',
            'list_description_2'=>'required|string|max:255',
            'list_3'=>'required|string',
            'list_title_3'=>'required|string|max:255',
            'list_description_3'=>'required|string|max:255',
        ];
    }
}
