<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function get(Request $request)
    {
        $offset = 0;
        $limit = 10;

        if ($request->has('page')) {
            if (is_numeric($request->page)) {
                $offset = ($request->page - 1) * 20;
            }
            $limit = 20;
        }

        $vouchers = Voucher::withCount(['pemakaian' => function ($query) use ($request) {
            $query->where('karyawan_id', $request->user()->id)->batal(false);
        }])
        ->with('kriteria')->where(function ($query) use ($request) {
            $query->where('jenis', 'Voucher umum')
                ->orWhereHas('penerimaVoucher', function ($query) use ($request) {
                    $query->where('id', $request->user()->id);
                });
        })->berlaku()->offset($offset)->limit($limit)->get()
        ->filter(function ($item) {
            $kriteria = $item->kriteria->where('nama', 'maksimal pemakaian')->first();
            if ($kriteria) {
                // jika pemakaian sama atau melebihi maksimal pakai
                if ($item->pemakaian_count >= $kriteria->nominal) {
                    return false;
                }
            } else {
                // voucher sekali pakai dan udah pernah di pakai
                if ($item->pemakaian_count) {
                    return false;
                }
            }

            return true;
        })
        ->values()
        ->map(function ($item) {
            $berlaku = 'S/D: ' . dateFormat($item->tanggal_akhir, 'Y-m-d');
            $diff = date_diff(now(), date_create($item->tanggal_akhir));
            if ($diff->format('%d') != '0') {
                $berlaku = 'Berakhir dlm: ' . $diff->format('%d Hari');
            } elseif ($diff->format('%h') != '0') {
                $berlaku = 'Berakhir dlm: ' . $diff->format('%h Jam');
            } elseif ($diff->format('%i') != '0') {
                $berlaku = 'Akan segera berakhir';
            }

            return [
                'id' => $item->id,
                'kode' => $item->kode_voucher,
                'nama' => $item->nama,
                'nominal' => $item->nominal,
                'tanggal_awal' => $item->tanggal_awal,
                'tanggal_akhir' => $item->tanggal_akhir,
                'sisa_berlaku' => $berlaku,
                'pemakaian' => $item->pemakaian_count,
                'syarat' => $item->kriteria->map(function ($item) {
                    return [
                        'nama_syarat' => $item->nama,
                        'nominal' => $item->nominal
                    ];
                })
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $vouchers,
        ]);
    }
}
