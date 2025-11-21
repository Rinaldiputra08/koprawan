<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class TitipanRequest extends FormRequest
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
        if (Route::currentRouteName() == 'titipan.approve') {
            return [
                'approval' => 'required',
                'keterangan_approval' => Rule::requiredIf(function () {
                    return !request('approval');
                }),
            ];
        } elseif (Route::currentRouteName() == 'titipan.batal') {
            return [
                'batal' => 'required',
                'keterangan_batal' => 'required',
            ];
        }
        return [
            'judul' => 'required',
            'nama' => 'required',
            'harga_jual' => 'required',
            'deskripsi' => 'required',
            'tanggal_awal_penjualan' => 'required',
            'tanggal_akhir_penjualan' => 'required',
        ];
    }
}
