<?php

namespace App\Repositories;

use App\Models\MasterData\Karyawan;
use App\Models\MasterData\Produk;
use App\Models\MasterData\Voucher;
use App\Models\Penjualan\Penjualan;
use App\Models\Penjualan\Piutang;
use App\Models\Titipan\Titipan;
use Illuminate\Support\Str;
use Mavinoo\Batch\BatchFacade;

class PenjualanLangsungRepository
{

    public function getProdukById($id, $column = null)
    {
        return Produk::active()->where($column ?? 'id', $id)->first();
    }

    public function getListedProduk(array $id)
    {
        return Produk::with('diskon')->whereIn('id', $id)->get();
    }

    public function getListTitipan(array $id)
    {
        return Titipan::whereIn('id', $id)->get();
    }

    public function getDataKaryawan($nik)
    {
        $is_uuid = Str::isUuid($nik);
        return Karyawan::with([
            'piutang' => function ($query) {
                $periode = getCurrentPeriode();
                $query->where('periode', $periode);
            },
            'voucher' => function ($query) {
                $query->where([
                    ['tanggal_awal', '<=', now()],
                    ['tanggal_akhir', '>=', now()]
                ]);
            },
            'limit' => function ($query) {
                $query->active();
            },
            'pemakaianVoucher',
            'voucher.kriteria',
        ])->where($is_uuid ? 'uuid' : 'nik', $nik)->first();
    }

    public function getVoucherUmum()
    {
        return Voucher::with('kriteria')->where([
            ['jenis', 'Voucher umum'],
            ['tanggal_awal', '<=', now()],
            ['tanggal_akhir', '>=', now()]
        ])->get();
    }

    public function getProduk($search = null)
    {
        return Produk::with('diskon')->active()
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('kode', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%");
                }
            })->limit(10)->get();
    }

    public function getProdukTitipan($search = null)
    {
        return Titipan::active()
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('nama_produk', 'like', "%{$search}%");
                }
            })->limit(10)->get();
    }

    public function store($request)
    {
        $penjualan = Penjualan::create([
            'nomor' => numbering((new Penjualan())->getTable(), 'nomor', 'PP' . date('ym')),
            'karyawan_id' => $request->karyawan_id,
            'total' => $request->total_harga,
            'diskon' => $request->diskon,
            'grand_total' => $request->grand_total,
            'status' => 'Selesai',
            'jenis' => 'Langsung',
            'user_id' => $request->user()->id,
            'user_input' => $request->user()->name,
        ]);

        return $penjualan;
    }

    public function updateStokProduk($data_update)
    {
        return BatchFacade::update(new Produk(), $data_update, 'id');
    }

    public function upsertPiutang($penjualan, $piutang)
    {
        $penjualan->refresh();
        if (!$piutang) $piutang = new Piutang;
        $periode = getCurrentPeriode();
        $piutang->karyawan_id = $penjualan->karyawan_id;
        $piutang->nominal = ($piutang->nominal ?? 0) + $penjualan->grand_total;
        $piutang->tanggal_terakhir_beli = $penjualan->tanggal;
        $piutang->periode = $piutang->periode ?? $periode;
        $piutang->save();
    }

    public function getVoucher($ids)
    {
        return Voucher::whereIn('id', $ids)->get();
    }
    public function batal($request, $penjualanLangsung)
    {
        $penjualanLangsung->tanggal_batal = now();
        $penjualanLangsung->keterangan_batal = $request->keterangan;
        $penjualanLangsung->user_batal_id = $request->user()->id;
        $penjualanLangsung->user_batal = $request->user()->name;
        $penjualanLangsung->batal = 1;
        $penjualanLangsung->save();

        return $penjualanLangsung;
    }
}
