<?php

namespace App\Repositories;

use App\Models\MasterData\Foto;
use App\Models\MasterData\Kategori;
use App\Models\MasterData\Merek;
use App\Models\MasterData\Produk;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProdukRepository
{
    public function getKategoriMerek()
    {
        $kategori = Kategori::active()->get();
        $merek = Merek::active()->get();
        return compact('kategori', 'merek');
    }

    public function getKategoriMerekEdit()
    {
        $kategori = Kategori::all();
        $merek = Merek::all();
        return compact('kategori', 'merek');
    }

    public function getProduk($search = null)
    {
        return Produk::active()
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%");
                }
            })->limit(10)->get();
    }

    public function getProdukById($id, $column = null)
    {
        return Produk::active()->where($column ?? 'id', $id)->first();
    }

    public function store(Request $request)
    {
        return Produk::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'uuid' => Str::uuid(),
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'kategori_id' => $request->kategori,
            'merek_id' => $request->merek,
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul),
            'deskripsi' => $request->deskripsi,
            'user_id' => $request->user()->id,
            'user_input' => $request->user()->name
        ]);
    }

    public function uploadFoto($file_upload, Produk $produk)
    {
        $upload = new UploadService;
        foreach ($file_upload as $foto) {
            $nama_file = time() . rand() . Str::random(10);
            $foto_pameran = $upload->uploadFromBase64($foto, 'produk', $nama_file);
            $produk->foto()->create(['nama_file' => $foto_pameran]);
        }
    }

    public function setFotoThumbnail(Request $request, Produk $produk)
    {
        $produk->foto()->update(['thumbnail' => 0]);

        $i = 0;
        $data_foto = Foto::where('referensi_id', $produk->id)->where('referensi_type', Produk::class)->get();

        foreach ($data_foto as $foto) {
            if ($request->foto_thumbnail[0] == $i) {
                $foto->thumbnail = 1;
                $foto->save();
            }
            $i++;
        }
    }

    public function deleteFoto($file_remove, Produk $produk)
    {
        $upload = new UploadService;
        foreach ($produk->foto as $foto) {
            if (in_array($foto->id, $file_remove)) {
                $foto->delete();
                $upload->deleteFoto($foto->nama_file, 'public/images/produk/');
            }
        }
    }

    public function update(Request $request, Produk $produk)
    {
        $produk->kode = $request->kode;
        $produk->nama = $request->nama;
        $produk->deskripsi = $request->deskripsi;
        $produk->judul = $request->judul;
        $produk->harga_beli = $request->harga_beli;
        $produk->harga_jual = $request->harga_jual;
        $produk->kategori_id = $request->kategori;
        $produk->merek_id = $request->merek;
        $produk->aktif = $request->aktif;
        $produk->save();

        return $produk;
    }
}
