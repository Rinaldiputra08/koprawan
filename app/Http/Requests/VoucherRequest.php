<?php

namespace App\Http\Requests;

use App\Models\MasterData\Voucher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\DateGreater;
use Illuminate\Http\Request;

class VoucherRequest extends FormRequest
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
    public function rules(Voucher $voucher, Request $request)
    {
        return [
            'nama' => ['required', Rule::unique($voucher->getTable())->ignore($this->voucher)],
            'tanggal_awal' => ['required'],
            'tanggal_akhir' => ['required', new DateGreater($request->input('tanggal_awal'))],
            'jam_awal' => 'required',
            'jam_akhir' => 'required',
            'menit_awal' => 'required',
            'menit_akhir' => 'required',
            'nominal' => ['required'],
            'has_kriteria' => ['required'],
            'kriteria'=>[Rule::requiredIf(function () use ($request) {
                return $request->has_kriteria;
            })],
            'jenis'=>['required'],
        ];
    }
}
