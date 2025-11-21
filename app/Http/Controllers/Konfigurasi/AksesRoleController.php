<?php

namespace App\Http\Controllers\Konfigurasi;

use App\DataTables\AksesRoleDataTable;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;

class AksesRoleController extends Controller
{
    public function index(AksesRoleDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi/akses-role');
    }

    public function edit(Role $akses_role)
    {
        $role = $akses_role;
        $menus = Menu::with('permissions')->orderBy('menu.no_urut')->get();
        return view('konfigurasi.akses-role-show', compact('role', 'menus'));
    }

    public function update(Request $request, Role $akses_role)
    {
        $role = $akses_role;
        $role->syncPermissions($request->permissions);
        return response()->json([
            "status" => "success",
            "message" => "Berhasil merubah data"
        ]);
    }
}
