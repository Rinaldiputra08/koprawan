<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\FirebaseIid;
use App\Models\MasterData\DataCustomer;
use App\Models\MasterData\Karyawan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    private $refresh_token_expire = 60 * 24 * 30 * 3;

    private function _message()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Username atau password salah'
        ], 403);
        exit;
    }

    public function LoginKaryawan(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'password' => 'required'
        ]);
        $user = Karyawan::where('nik', $request->nik)->first();
        if (!$user) {
            return $this->_message();
        }

        $verify_password = Hash::check($request->password, $user->password);

        if (!$verify_password) {
            return $this->_message();
        }

        $token = auth()->guard('api')->setTTL(60 * 24 * 30 * 36)->claims(['nama' => $user->nama, 'id' => $user->uuid])->login($user);

        $user->foto = asset('storage/images/karyawan/medium_' . $user->foto);
        return response()->json([
            'status' => 'success',
            'message' => 'Login successfully',
            'token' => $token,
            'data' => $user,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'password' => 'required'
        ]);
        $user = Karyawan::create([
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'no_hp' => $request->no_hp,
            'uuid' => \Str::uuid()
        ]);
        $token = auth()->guard('api')->claims(['nama' => $user->nama, 'id' => $user->uuid])->login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil registrasi',
            'data' => $user,
            'token' => $token,
        ]);
    }

    public function checkPasswordCustomer(Request $request)
    {
        $user = DataCustomer::where('no_hp', $request->no_hp)->first();

        if (!$user) {
            return responseNotFound();
        }

        $verify_password = Hash::check($request->password, $user->password);

        if (!$verify_password) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid password'
            ], 403);
        }

        $token = auth()->guard('api')->claims(['nama' => $user->nama, 'id' => $user->uuid])->login($user);

        $refresh_token = auth()->guard('api')->setTTL($this->refresh_token_expire)->claims(['nama' => $user->nama, 'id' => $user->uuid])->login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'token' => $token,
            'refresh_token' => $refresh_token,
            'data' => $user,
        ])->withCookie(cookie('refresh_token', $refresh_token, $this->refresh_token_expire, null, null, true, true, false, 'None'));
        // ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');

    }

    public function logout()
    {
        auth()->logout();
        return response()->noContent()->withoutCookie('refresh_token');
    }

    public function refreshToken(Request $request)
    {
        $token = auth()->guard('api')->refresh(false, false);
        // $refresh_token = auth()->guard('api')->setTTL($this->refresh_token_expire)->claims(['nama' => $user->nama, 'id' => $user->uuid])->login($user);

        return response()->json([
            'token' => $token,
        ]);
        // ->withCookie(cookie('refresh_token', $refresh_token, $this->refresh_token_expire));

        // validasi cookie
        $cookie = $request->cookie('refresh_token');
        if (!$cookie) {
            return response()->json([
                'status' => 'error',
                'message' => 'error cookie'
            ], 400);
        }

        try {
            $jwt = JWT::decode($cookie, new Key(env('JWT_SECRET'), 'HS256'));
            $user = DataCustomer::where('uuid', $jwt->id)->first();

            $token = auth()->guard('api')->refresh(false, false);
            $refresh_token = auth()->guard('api')->setTTL($this->refresh_token_expire)->claims(['nama' => $user->nama, 'id' => $user->uuid])->login($user);

            return response()->json([
                'token' => $token,
            ])->withCookie(cookie('refresh_token', $refresh_token, $this->refresh_token_expire));
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 401);
        }
    }


    // using sanctum
    // public function loginCustomer_(Request $request)
    // {
    //     $user = DataCustomer::where('no_hp', $request->no_hp)->first();

    //     if (!$user) {
    //         return responseNotFound();
    //     }

    //     $verify_password = Hash::check($request->password, $user->password);

    //     if (!$verify_password) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Invalid password'
    //         ], 403);
    //     }

    //     $token = $user->createToken('customer')->plainTextToken;

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'success',
    //         'token' => $token,
    //         'data' => $user,
    //     ])->withCookie(cookie('token', $token));
    // }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required',
    //         'password' => 'required'
    //     ]);

    //     $user = User::select('username', 'password', 'level', 'email')
    //         ->where('username', $request->username)
    //         ->where('blokir', 'N')
    //         ->first();

    //     if (!$user) {
    //         return $this->_message();
    //     }
    //     $user->makeVisible('password');

    //     $verify_password = Hash::check($request->password, $user->password);
    //     if (!$verify_password) {
    //         return $this->_message();
    //     }

    //     $user->password = $user->password . $user->password;

    //     /** store to firebase id */
    //     FirebaseIid::updateOrInsert(
    //         ['instance_id' => $request->token],
    //         [
    //             'instance_id' => $request->token,
    //             'email' => $user->email,
    //             'last_akses' => now(),
    //             'jenis' => 'APP',
    //             'login_app' => '1'
    //         ]

    //     );


    //     return response()->json([
    //         'status' => 'success',
    //         'message' => $user,
    //         'data' => $user
    //     ]);
    // }

    // public function validateUser(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required',
    //         'password' => 'required'
    //     ]);


    //     $user = User::from('users as u')->select('username', 'password', 'level', 'f.instance_id', 'f.login_app')
    //         ->leftJoin('firebase_iid as f', 'f.email', '=', 'u.email')
    //         ->where('u.username', $request->username)
    //         ->where('u.blokir', 'N')
    //         ->where('f.instance_id', $request->token)
    //         ->first();

    //     if (!$user) {
    //         return $this->_message();
    //     }
    //     $user->makeVisible('password');

    //     $verify_password = $user->password . $user->password == $request->password;
    //     if (!$verify_password) {
    //         return $this->_message();
    //     }

    //     $user->password = $user->password . $user->password;

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => $user,
    //         'data' => $user,
    //     ]);
    // }
}
