<?php

namespace App\Http\Requests;

use App\Models\MasterData\Karyawan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KaryawanRequest extends FormRequest
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
    public function rules(Karyawan $karyawan)
    {
        return [
            'nik' => ['required', Rule::unique($karyawan->getTable())->ignore($this->karyawan)],
            'nama' => 'required',
            'divisi' => 'required',
        ];
    }
}
