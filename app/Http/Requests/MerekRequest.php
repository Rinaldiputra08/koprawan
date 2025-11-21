<?php

namespace App\Http\Requests;

use App\Models\MasterData\Merek;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MerekRequest extends FormRequest
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
    public function rules(Merek $merek)
    {
        return [
            'nama' => ['required', Rule::unique($merek->getTable())->ignore($this->merek)]
        ];
    }
}
