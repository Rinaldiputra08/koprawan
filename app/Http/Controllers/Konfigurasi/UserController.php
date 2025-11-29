<?php

namespace App\Http\Controllers\Konfigurasi;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Services\UploadService;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(UserDataTable $dataUser)
    {
        return $dataUser->render('konfigurasi/user');
    }

    public function create(User $user)
    {
        $roles = Role::all();
        return view('konfigurasi/user-show', ['getDetail' => $user, 'roles' => $roles]);
    }

    public function store(StoreUserRequest $request, UploadService $upload)
    {
        // dd($request);
        if ($request->hasFile('foto_upload')) {
            $data = $request->all();
            $imageName = $upload->uploadFoto($request, 'foto_upload');

            $data['foto'] = $imageName; // simpan ke kolom foto
            $data['password'] = bcrypt($request->password);
            $data['tgl_lahir'] = convertDate($request->tgl_lahir);

            unset($data['_token']);
            unset($data['password_confirmation']);
            unset($data['foto_upload']);

            $user = User::create($data);
            $user->syncRoles($request->roles);

            return response()->json([
                "status" => "success",
                "message" => "Data berhasil disimpan"
            ]);
        }
    }

    public function show($id)
    {
        //
    }

    public function profile()
    {
        $getDetail = auth()->user();
        // dd($getDetail);
        $roles = Role::all();
        return view('konfigurasi.user-profile', compact('getDetail', 'roles'));
    }
    public function edit(User $user)
    {
        $getDetail = $user;
        $roles = Role::all();
        return view('konfigurasi.user-show', ['getDetail' => $getDetail, 'roles' => $roles]);
    }

    public function update(Request $request, User $user, UploadService $upload)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('blokir')) {
            $user->blokir = $request->blokir;
        }
        $user->alamat = $request->alamat;
        $user->no_telp = $request->no_telp;
        $user->tgl_lahir = convertDate($request->tgl_lahir);

        /** using file */
        // if ($request->hasFile('foto')) {
        //     if ($user->foto) {
        //         $upload->deleteFoto($user->foto);
        //     }
        //     $imageName = $upload->uploadFoto($request);
        // }

        /** using base64 */
        if ($request->foto_upload != '') {
            if ($user->foto) {
                $upload->deleteFoto($user->foto);
            }
            $imageName = $upload->uploadFromBase64($request->foto_upload, 'profile', time() . $request->file('foto')->getClientOriginalName());
            $user->foto = $imageName;
        }

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        if (!$request->ajax()) {
            return redirect()->back()->with('message', 'Data berhasil diubah');
        }
        return response()->json([
            "status" => "success",
            "message" => "Data berhasil diubah"
        ]);
    }

    public function destroy($id)
    {
        //
    }
}
