<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAsController extends Controller
{
    private function _validateSession()
    {
        if (!session('isAdmin')) {
            abort(403, 'Unauthorized');
        }
    }
    public function get()
    {
        $this->_validateSession();

        $users = User::select('username', 'name')->where('blokir', 'N')->get();
        return view('auth.login-as', compact('users'));
    }

    public function process(Request $request)
    {
        $this->_validateSession();

        $user = User::where('username', $request->username)
            ->where('blokir', 'N')->first();
        Auth::login($user);
        return redirect()->route('dashboard.index');
    }
}
