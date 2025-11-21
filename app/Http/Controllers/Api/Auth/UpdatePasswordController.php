<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'password_lama' => 'required|current_password:api',
            'password_baru' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/',
            'konfirmasi_password' => 'required|same:password_baru'
        ], [
            'password_lama.current_password' => 'Password lama tidak sesuai',
            'password_baru.min' => 'Minimal 8 karakter',
            'password_baru.regex' => 'Harus berisi huruf, angka dan minimal satu huruf besar',
            'konfirmasi_password.same' => 'Konfirmasi password tidak sesuai'
        ]);

        // proses update password
        try {
            // verify password baru, harus beda dengan password sebelumnya
            $user = $request->user();
            $verify_password = Hash::check($request->password_baru, $user->password);
            if ($verify_password) {
                throw new \Exception('Password baru tidak boleh sama dengan password sebelumnya.', 1);
            }

            $user->password = bcrypt($request->password_baru);
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Ganti password berhasil',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return responseError($th);
        }
    }
}
