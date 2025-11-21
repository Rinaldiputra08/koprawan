<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PenerimaanProdukRequest extends FormRequest
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
            'tanggal_penerimaan' => 'required',
            'tagihan' => 'required',
            'supplier' => 'required',
            'nomor_pemesanan' => Rule::requiredIf(function () {
                return request('pemesanan');
            })
        ];
    }
}
