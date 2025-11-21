<?php

namespace App\Http\Controllers\Api\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Produk;
use App\Models\Penjualan\Cart;
use App\Models\Titipan\Titipan;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCart(Request $request)
    {
        $user = $request->user();
        $carts = Cart::with('produk.fotoThumbnail', 'produk.diskon')->active()->where('karyawan_id', $user->id)->get();

        $carts = $carts->map(function ($cart) {
            $new_cart['qty'] = $cart->qty;
            $new_cart['id'] = $cart->id;
            $new_cart['product_id'] = $cart->produk->id;
            $new_cart['slug'] = $cart->produk->slug;
            $new_cart['judul'] = $cart->produk->judul;
            $new_cart['harga_jual'] = $cart->produk->harga_jual;
            if ($cart->produk->diskon) {
                $new_cart['diskon'] = $cart->produk->diskon->nominal;
            }
            $new_cart['stock_free'] = $cart->produk->stock_free;
            if ($cart->produk->fotoThumbnail) {
                $new_cart['foto_thumbnail'] = asset('storage/images/produk/small_' . $cart->produk->fotoThumbnail->nama_file);
            } else {
                $new_cart['foto_thumbnail'] = asset('assets/images/image-placeholder.jpg');
            }
            return $new_cart;
        });

        return $carts;
    }

    public function index(Request $request)
    {
        $carts = $this->getCart($request);

        return response()->json([
            'status' => 'success',
            'data' => $carts,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer',
        ]);

        $cart = $request->user()->carts()->where('id', $id)->first();

        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart tidak ditemukan'
            ], 403);
        }
        $produk = $cart->produk;

        if ($request->qty > $produk->stock_free) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stock tidak mencukupi'
            ], 403);
        }

        $cart->qty = $request->qty;
        $update = $cart->save();

        return response()->json([
            'status' => 'success',
            'message' => $update ? 'Berhasil memperbaharui qty' : 'Tidak ada data yang di perbaharui'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required',
        ]);

        if ($request->jenis == 'produk_titipan') {
            $produk = Titipan::where('id', $request->produk_id)->first();
        } else {
            $produk = Produk::where('id', $request->produk_id)->first();
        }
        if (!$produk) {
            return responseNotFound();
        }

        if ($produk->stock_free == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal, Stock tidak mencukupi'
            ], 403);
        }

        $cart = $produk->carts()->where('produk_id', $request->produk_id)
            ->where('karyawan_id', $request->user()->id)->where('terjual', 0)->first();

        if ($cart) {
            if ($produk->stock_free < $cart->qty + 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal, Stock tidak mencukupi'
                ], 403);
            }
            $cart->qty = $cart->qty + 1;
            $cart->save();
        } else {
            $cart = $produk->carts()->create([
                'karyawan_id' => $request->user()->id,
                'qty' => 1,
                'tanggal' => now()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil menambah ke keranjang',
            'data' => $cart
        ]);
    }

    public function count(Request $request)
    {
        $count = $request->user()->carts()->active()->get()->sum('qty');

        return response()->json([
            'status' => 'success',
            'data' => $count
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->carts()->where('id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil menghapus barang',
        ]);
    }
}
