<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchCodeRequest extends FormRequest
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
            'query'     => 'required|string',
            'page'      => 'integer|min:1',
            'per_page'  => 'integer|between:1,100',
            'sorting'   => 'string',
        ];
    }
}
