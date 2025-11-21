<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SetupApplicationRequest extends FormRequest
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
            'nama' => [
                Rule::requiredIf(function () {
                    return request()->isMethod('POST');
                }),
                Rule::unique('setup_applications', 'name'),
                'regex:/(^([a-zA-Z_]+(\d+)?$))/u'
            ],
            'jenis' => 'required',
            'nilai' => [
                'required',
                request('jenis') == 'json' ? 'json' : null
            ]
        ];
    }
}
