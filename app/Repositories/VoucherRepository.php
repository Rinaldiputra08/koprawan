<?php

namespace App\Repositories;


use App\Models\MasterData\Voucher;
use App\Models\MasterData\VoucherKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherRepository
{
    public function store(Request $request)
    {
        return Voucher::create([
            'uuid' => Str::uuid(),
            'nama' => $request->nama,
            'tanggal_awal' => $request->tanggal_awal . " " . $request->jam_awal . ':' . $request->menit_awal,
            'tanggal_akhir' => $request->tanggal_akhir . " " . $request->jam_akhir . ':' . $request->menit_akhir,
            'ketentuan'       => $request->has_kriteria,
            'nominal'        => $request->nominal,
            'jenis'          => $request->jenis,
            'user_id' => $request->user()->id,
            'kode_voucher' => $request->kode_voucher ?? strtoupper(Str::random(7)),
        ]);
    }

    public function getKriteria($ids)
    {
        return VoucherKriteria::whereIn('id', $ids)->get();
    }
}
