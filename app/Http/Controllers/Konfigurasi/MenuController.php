<?php

namespace App\Http\Controllers\Konfigurasi;

use App\DataTables\MenuDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuRequest;
use App\Models\Menu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Mavinoo\Batch\BatchFacade;

class MenuController extends Controller
{

    public function index(MenuDataTable $dataMenu)
    {
        return $dataMenu->render('konfigurasi.menu');
    }

    public function create()
    {
        $menu = new Menu();
        return view('konfigurasi.menu-show', ['getDetail' => $menu, "level" => $this->_getLevelMenu()]);
    }

    public function store(Request $request)
    {
        Cache::forget('navigation');
        Menu::create($request->all());
        return response()->json([
            "status" => "success",
            "message" => "berhasil menambah data"
        ]);
    }

    private function _getLevelMenu()
    {
        return Menu::whereNull('main_menu')->where('aktif', 1)->get();
    }

    public function edit(Menu $menu)
    {
        $getDetail = $menu;
        return view('konfigurasi.menu-show', ['getDetail' => $getDetail, 'level' => $this->_getLevelMenu()]);
    }

    public function update(StoreMenuRequest $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        unset($data['level']);
        Cache::forget('navigation');
        Menu::where('id', $id)->update($data);
        return response()->json([
            "status" => "success",
            "message" => "data berhasil diperbaharui"
        ]);
    }

    public function destroy(Menu $menu)
    {
        $menu->aktif = 0;
        $menu->save();
        Cache::forget('navigation');
        return response()->json([
            "status" => "success",
            "message" => "Data berhasil dihapus"
        ]);
    }

    public function sort()
    {
        $menus = Menu::whereNull('main_menu')->get();
        $data = [];
        $i = 0;
        foreach ($menus as $menu) {
            $i++;
            $data[] = ['id' => $menu->id, 'no_urut' => $i];
            foreach ($menu->subMenus as $sub) {
                $i++;
                $data[] = ['id' => $sub->id, 'no_urut' => $i];
            }
        }

        Cache::forget('navigation');
        BatchFacade::update(new Menu(), $data, 'id');

        return response()->json(['status' => 'success', 'message' => 'Berhasil mengurutkan menu']);
    }
}
