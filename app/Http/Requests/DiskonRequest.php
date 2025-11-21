<?php

namespace App\Http\Requests;

use App\Rules\DateGreater;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class DiskonRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'tanggal_awal' => 'required',
            'tanggal_akhir' => ['required', new DateGreater($request->input('tanggal_awal'))],
            'jam_awal' => 'required',
            'jam_akhir' => 'required',
            'menit_awal' => 'required',
            'menit_akhir' => 'required',
            'nominal' => ['required'],
            'kode_produk' => 'required',
        ];
    }
}
