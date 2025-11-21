<?php

namespace App\Http\Controllers\Konfigurasi;

use App\DataTables\RoleDataTable;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.role');
    }

    public function create(Role $role)
    {
        return view('konfigurasi/role-show',['data' => $role]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Role::create(['name' => $request->name]);
        return response()->json(['status' => 'success', 'message' => 'Berhasil menambah data']);
    }

    public function show(Role $role)
    {
        //
    }

    public function edit(Role $role)
    {
        return view('konfigurasi/role-show',['data' => $role]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required']);
        
        $role->name = $request->name;
        $role->save();

        return response()->json(['status' => 'success', 'message' => 'Berhasil merubah data']);
    }

    public function destroy(Role $role)
    {
        //
    }
}
