<?php

namespace App\Http\Requests;

use App\Models\MasterData\VoucherKriteria;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VoucherKriteriaRequest extends FormRequest
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
    public function rules(VoucherKriteria $voucher_kriterium)
    {
        return [
            'nama' => ['required'],
            'nominal' => ['required'],
        ];
    }
}
