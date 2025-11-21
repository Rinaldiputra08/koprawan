<?php

namespace App\Http\Requests;

use App\Models\MasterData\Produk;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProdukRequest extends FormRequest
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
    public function rules(Produk $produk, Request $request)
    {

        return [
            'kode' => ['required', Rule::unique($produk->getTable())->ignore($this->produk)],
            'nama' => 'required',
            'judul' => 'required',
            'deskripsi' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required', 
            'kategori' => 'required',
            'merek' => 'required',
            'foto_thumbnail' => [Rule::requiredIf($request->remove_upload_foto || $request->upload_foto),'max:1'],
        ];
    }

    public function messages()
    {
        return [
            'foto_thumbnail.max' => 'Foto sebagai thumbnail tidak boleh lebih dari satu',
            'foto_thumbnail.required' => 'Foto thumbnail harus dipilih'
        ];
    }
}
