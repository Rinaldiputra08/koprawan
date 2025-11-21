<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginGoogleController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(){
        try {
            $user = Socialite::driver('google')->stateless()->user();
            
            $isUserExist = User::where('google_id', $user->getId())
            ->orWhere('email_google', $user->getEmail())
            ->orWhere('email', $user->getEmail())
            ->first();
            if($isUserExist){
                if($isUserExist->google_id == null){
                    $isUserExist->google_id = $user->getId();
                    $isUserExist->save();
                }
                Auth::login($isUserExist);
                return redirect()->intended('/dashboard');
            }else{
                return redirect()->route('login')->withErrors(['login_social' => 'Akun email google kamu tidak terdaftar dalam sistem']);
            }
        } catch (\Throwable $th) {
            return redirect()->route('login')->withErrors(['login_social' => 'Terjadi kesalahan pada otentikasi google, silahkan coba lagi']);
        }
    }
}
