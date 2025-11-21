<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\RatingRequest;
use App\Models\MasterData\Rating;
use App\Models\Penjualan\PenjualanDetail;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    public function store(RatingRequest $request)
    {
        DB::beginTransaction();
        try {
            $penjualan_detail = PenjualanDetail::with(['penjualan.serahTerima', 'produk'])->where('id', $request->penjualan_detail_id)->first();

            if (!$penjualan_detail) {
                return responseError('Penjualan Tidak ada!');
            }

            if ($penjualan_detail->penjualan->karyawan_id != $request->user()->id) {
                return responseError('Kode produk salah!');
            }

            if (!$penjualan_detail->penjualan->serahTerima) {
                return responseError('Penjualan belum serah terima!');
            }

            $rating_produk = Rating::where('penjualan_detail_id', $penjualan_detail->id)
                ->where('karyawan_id', $request->user()->id)->first();

            if ($rating_produk) {
                return responseError('Kamu sudah memberi rating produk ini!');
            }

            $data = Rating::create([
                'produk_id' => $penjualan_detail->produk_id,
                'produk_type' => $penjualan_detail->produk_type,
                'penjualan_detail_id' => $penjualan_detail->id,
                'rating' => $request->rating,
                'komentar' => $request->komentar,
                'karyawan_id' => $request->user()->id,
            ]);

            $produk = $penjualan_detail->produk;

            $produk->rating_count += 1;
            $produk->total_rating += $request->rating;
            $produk->rating = $produk->total_rating / $produk->rating_count;
            $produk->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memberi rating',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseError();
        }
    }
}
