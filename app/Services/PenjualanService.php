<?php
namespace App\Services;

use App\Helpers\ResponseException;
use App\Models\Penjualan\PemakaianVoucher;
use App\Repositories\PenjualanRepository;
use Illuminate\Http\Request;

class PenjualanService 
{
    public function transVoucher(Request $request, PenjualanRepository $repository)
    {
        $trans_voucher = [];
        $vouchers = $repository->getVoucher($request->voucher);
        foreach ($vouchers as $voucher) {
            // check if voucher is already used or not
            if ($voucher->ketentuan) {
                foreach ($voucher->kriteria as $kriteria) {
                    $pemakaian_voucher = $voucher->pemakaian->filter(function ($item) {
                        return $item->karyawan_id == request()->user()->id;
                    });

                    if ($kriteria->nama == 'maksimal pemakaian' && $pemakaian_voucher->count() >= $kriteria->nominal) {
                        $voucher_used = $pemakaian_voucher;
                    }
                }
            } else {
                $pemakaian_voucher = $voucher->pemakaian->filter(function ($item) {
                    return $item->karyawan_id == request()->user()->id;
                });

                if ($pemakaian_voucher->count() > 0) {
                    $voucher_used = $pemakaian_voucher;
                }
            }
            if (isset($voucher_used)) {
                throw new ResponseException('Voucher sudah digunakan', $voucher_used->values());
            }
            // data voucher
            $trans_voucher[] = new PemakaianVoucher([
                'voucher_id' => $voucher->id,
                'nama' => $voucher->nama,
                'nominal' => $voucher->nominal,
                'karyawan_id' => $request->karyawan_id,
            ]);
            $request->grand_total -= $voucher->nominal;
        }
        return $trans_voucher;
    }
}