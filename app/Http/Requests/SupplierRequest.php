<?php

namespace App\Http\Requests;

use App\Models\MasterData\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
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
    public function rules(Supplier $supplier)
    {
        return [
            'nama' => ['required', Rule::unique($supplier->getTable())->ignore($this->supplier)]
        ];
    }
}
