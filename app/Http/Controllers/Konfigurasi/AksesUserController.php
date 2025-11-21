<?php

namespace App\Http\Controllers\Konfigurasi;

use App\DataTables\AksesUserDataTable;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;

class AksesUserController extends Controller
{
    public function index(AksesUserDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi/akses-user');
    }
    public function edit(User $akses_user)
    {
        $user = $akses_user;
        $menus = Menu::with('permissions')->orderBy('menu.no_urut')->get();
        return view('konfigurasi.akses-user-show', compact('user', 'menus'));
    }

    public function update(Request $request, User $akses_user)
    {
        $akses_user->syncPermissions($request->permissions);
        return response()->json([
            "status" => "success",
            "message" => "Berhasil merubah data"
        ]);
    }
}
