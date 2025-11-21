<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function get()
    {
        $categories = Kategori::with('foto')->active()->select('id','nama')->get()->toArray();

        $categories = collect($categories)->map(function($category){
            
            if($category['foto']){
                $category['foto'] = asset('/storage/images/kategori-produk/small_'.$category['foto']['nama_file']);
            }else{
                $category['foto'] = asset('assets/images/image-placeholder.jpg');
            }

            return $category;
        });
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $categories
        ]);
    }
}
