<?php

namespace App\Http\Requests;

use App\Models\MasterData\Kategori;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KategoriRequest extends FormRequest
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
    public function rules(Kategori $kategori)
    {
        return [
            'nama' => ['required', Rule::unique($kategori->getTable())->ignore($this->kategori)],
        ];
    }
}
