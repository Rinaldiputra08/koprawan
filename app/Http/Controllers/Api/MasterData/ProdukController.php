<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request, $type)
    {
        $products = Produk::with('fotoThumbnail:id,nama_file,referensi_id,referensi_type')
            ->with('diskon');
        if ($type == 'latest') {
            $products = $products->orderBy('tanggal', 'desc');
        } else if ($type == 'promo') {
            $products = $products->whereHas('diskon')->orderBy('tanggal', 'desc');
        } else if ($type == 'best-seller') {
            $products = $products->orderBy('terjual', 'desc');
        } else if ($type == 'category') {
            if (!$request->has('filter')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'error',
                    'data' => [],
                ], 403);
            }
            $products = $products->with('kategori')->whereHas('kategori', function ($query) use ($request) {
                $query->where('nama', $request->filter);
            });
        }

        $offset = 0;
        $limit = 10;
        if ($request->has('page')) {
            if (is_numeric($request->page)) {
                $offset = ($request->page - 1) * 20;
            }
            $limit = 20;
        }
        $products = $products->select('id', 'harga_jual', 'slug', 'judul', 'deskripsi', 'stock_free', 'terjual', 'rating', 'rating_count', 'kategori_id')
            ->where('aktif', 1)->offset($offset)->limit($limit)->get();

        $products = collect($products->toArray())->map(function ($product) {
            if ($product['foto_thumbnail']) {
                $product['foto_thumbnail'] = asset('storage/images/produk/small_' . $product['foto_thumbnail']['nama_file']);
            } else {
                $product['foto_thumbnail'] = asset('assets/images/image-placeholder.jpg');
            }
            if ($product['diskon']) {
                $product['diskon'] = $product['diskon']['nominal'];
            }
            if (isset($product['kategori']) && $product['kategori']) {
                $product['kategori'] = $product['kategori']['nama'];
            }
            $product['jenis'] = 'produk_koperasi';
            return $product;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $products,
            'type' => $type,
        ]);
    }

    public function detail($slug)
    {
        $product = Produk::with('foto', 'diskon', 'rating:produk_id,produk_type,rating,komentar')
            ->select('id', 'harga_jual', 'slug', 'judul', 'deskripsi', 'stock_free', 'terjual', 'rating', 'rating_count')
            ->where('slug', $slug)->where('aktif', 1)->first();

        if (!$product) {
            return responseNotFound();
        }

        $product = $product->toArray();

        if ($product['foto']) {
            $product['foto'] = collect($product['foto'])->map(function ($foto) {
                $data['url'] = asset('storage/images/produk/medium_' . $foto['nama_file']);
                return $data;
            });
        } else {
            $product['foto'] = [['url' => $product['foto_thumbnail'] = asset('assets/images/image-placeholder.jpg')]];
        }

        if ($product['diskon']) {
            $product['diskon'] = $product['diskon']['nominal'];
        }
        $product['jenis'] = 'produk_koperasi';

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
