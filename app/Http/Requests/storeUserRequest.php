<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'email_google' => ['nullable', 'email', 'max:255', 'unique:users,email_google'],
            'password' => ['required', 'min:6'],
            'aktif' => ['nullable', 'boolean'],
            'foto' => ['nullable', 'image', 'max:2048'], // 2MB
            'alamat' => ['nullable', 'string'],
            'tgl_lahir' => ['nullable', 'date'],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'google_id' => ['nullable', 'string', 'max:255', 'unique:users,google_id'],
        ];
    }
}
