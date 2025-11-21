<?php

namespace App\Http\Controllers\Konfigurasi;

use App\DataTables\PermissionDataTable;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    public function index(PermissionDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi/permission');
    }

    public function create()
    {
        $menus = Menu::all();
        return view('konfigurasi/permission-show',['data' => new Permission(),'menus' => $menus]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        $permission = Permission::create(['name' => $request->name,'guard_name' => $request->guard_name]);
        $permission->menus()->sync([$request->menu_id]);
        return response()->json(['status' => 'success', 'message' => 'Berhasil menyimpan data']);
    }

    public function show($id)
    {
        //
    }

    public function edit(Permission $permission)
    {
        $menus = Menu::all();
        return view('konfigurasi/permission-show',['data' => $permission, 'menus' => $menus]);
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate(['name' => 'required']);
        
        $permission->name = $request->name;
        $permission->guard_name = $request->guard_name;
        $permission->save();

        $permission->menus()->sync([$request->menu_id]);

        return response()->json(['status' => 'success', 'message' => 'Berhasil merubah data']);
    }

    public function destroy($id)
    {
        //
    }
}
