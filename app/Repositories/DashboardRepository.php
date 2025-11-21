<?php

namespace App\Repositories;

use App\Models\MasterSales\PesananKendaraan;
use App\Models\MasterSales\SummaryFaktur;
use App\Models\MasterSales\TargetMarketing;
use App\Models\Prospek\Lead;
use App\Models\Prospek\PengajuanDiscount;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    public function getPengajuanDiskon($user)
    {
        return PengajuanDiscount::whereMonth('tgl_pengajuan_ulang', date('m'))
            // ->whereYear('tgl_pengajuan_ulang', 2020)
            ->whereYear('tgl_pengajuan_ulang', date('Y'))
            ->where(function ($query) use ($user) {
                if ($user->hasRole('sales_supervisor')) {
                    $query->where('kode_spv', $user->kode_supervisor);
                }

                if ($user->hasRole('sales')) {
                    $query->where([
                        ['nama_sales', $user->name],
                        ['fleet', '!=', 'Fleet SPV']
                    ]);
                }
            })
            ->select('no_pengajuan', 'no_spk', 'nama_sales', 'nama_customer')->get();
    }

    public function getPengajuanDiskonPeriode($tgl_awal, $tgl_akhir, $user)
    {
        return PengajuanDiscount::from('pengajuan_discount as pd')
            ->leftJoin('pengajuan_discount_ulang as pdu', function ($join) {
                $join->on('pd.id', '=', 'pdu.pengajuan_discount_id')
                    ->where('pdu.aktif', 'Y');
            })
            ->whereDate('pd.tgl_pengajuan_ulang', '>=', $tgl_awal)
            ->whereDate('pd.tgl_pengajuan_ulang', '<=', $tgl_akhir)
            ->where(function ($query) use ($user) {
                if ($user->hasRole('sales_supervisor')) {
                    $query->where('pd.kode_spv', $user->kode_supervisor);
                }

                if ($user->hasRole('sales')) {
                    $query->where([
                        ['pd.nama_sales', $user->name],
                        ['pd.fleet', '!=', 'Fleet SPV']
                    ])->orWhere([
                        ['pd.nama_sales', $user->name],
                        ['pdu.fleet', '!=', 'Fleet SPV']
                    ]);
                }
            })
            ->select('pd.no_pengajuan', DB::raw('date_format(pd.tgl_pengajuan_ulang, "%m/%y") as tanggal'), DB::raw('date_format(pd.tgl_pengajuan_ulang, "%d") as tgl'))
            ->orderBy('pd.tgl_pengajuan_ulang')
            ->get();
    }

    public function getSpk($user)
    {
        $pesanan_kendaraan = PesananKendaraan::from('pesanan_kendaraan as pk')
            ->select(
                'pd.kode_spv',
                'pd.no_spk as nomor',
                'pd.nama_customer',
                'pd.leasing',
                'pd.jenis_leasing',
                'pd.no_pengajuan',
                'pk.model',
                'u.kode_sales',
                'pd.cara_beli as jenispenjualan',
                DB::raw('if(isnull(pdu.fleet), pd.fleet, pdu.fleet) as fleet')
            )
            ->whereMonth('pk.tanggal', date('m'))
            ->whereYear('pk.tanggal', date('Y'))
            ->join('pengajuan_discount as pd', 'pd.no_spk', '=', 'pk.nomor')
            ->leftJoin('pengajuan_discount_ulang as pdu', function ($join) {
                $join->on('pd.id', '=', 'pdu.pengajuan_discount_id')
                    ->where('pdu.aktif', 'Y');
            })
            ->leftJoin('users as u', 'u.username', '=', 'pd.username_pemohon')
            ->get();

        if ($user->hasRole('sales_supervisor')) {
            $pesanan_kendaraan = $pesanan_kendaraan->filter(function ($pesanan) use ($user) {
                return $pesanan->kode_spv == $user->kode_supervisor;
            });
        }

        if ($user->hasRole('sales')) {
            $pesanan_kendaraan = $pesanan_kendaraan->filter(function ($pesanan) use ($user) {
                return $pesanan->kode_sales == $user->kode_sales and $pesanan->fleet != 'Fleet SPV';
            });
        }

        return $pesanan_kendaraan;

        // return DB::connection('sqlsrv')
        //     ->table('vw_spk')
        //     ->whereMonth('tanggal', date('m'))
        //     ->whereYear('tanggal', date('Y'))
        //     ->where([
        //         ['batal', 0],
        //         ['kode_supervisor', '!=', 'OFFCE']
        //     ])->where(function ($query) use ($user) {
        //         if ($user->hasRole('sales_supervisor')) {
        //             $query->where('kode_supervisor', $user->kode_supervisor);
        //         }

        //         if ($user->hasRole('sales')) {
        //             $query->where('kode_salesman', $user->kode_sales);
        //         }
        //     })
        //     ->select('nomor', 'namastnk')->get();
    }

    public function getSpkPeriode($tgl_awal, $tgl_akhir, $user)
    {
        $pesanan_kendaraan = PesananKendaraan::from('pesanan_kendaraan as pk')
            ->select(
                'pd.kode_spv',
                'pd.no_spk as nomor',
                'pk.tanggal',
                'pd.leasing',
                'pd.jenis_leasing',
                'pd.no_pengajuan',
                'pk.model',
                'u.kode_sales',
                'pd.cara_beli as jenispenjualan',
                DB::raw('if(isnull(pdu.fleet), pd.fleet, pdu.fleet) as fleet')
            )
            ->whereDate('pk.tanggal', '>=', $tgl_awal)
            ->whereDate('pk.tanggal', '<=', $tgl_akhir)
            ->join('pengajuan_discount as pd', 'pd.no_spk', '=', 'pk.nomor')
            ->leftJoin('pengajuan_discount_ulang as pdu', function ($join) {
                $join->on('pd.id', '=', 'pdu.pengajuan_discount_id')
                    ->where('pdu.aktif', 'Y');
            })
            ->leftJoin('users as u', 'u.username', '=', 'pd.username_pemohon')
            ->get();

        if ($user->hasRole('sales_supervisor')) {
            $pesanan_kendaraan = $pesanan_kendaraan->filter(function ($pesanan) use ($user) {
                return $pesanan->kode_spv == $user->kode_supervisor;
            });
        }

        if ($user->hasRole('sales')) {
            $pesanan_kendaraan = $pesanan_kendaraan->filter(function ($pesanan) use ($user) {
                return $pesanan->kode_sales == $user->kode_sales and $pesanan->fleet != 'Fleet SPV';
            });
        }

        return $pesanan_kendaraan;

        // return DB::connection('sqlsrv')
        //     ->table('vw_spk')
        //     ->whereDate('tanggal', '>=', $tgl_awal)
        //     ->whereDate('tanggal', '<=', $tgl_akhir)
        //     ->where(function ($query) use ($user) {
        //         if ($user->hasRole('sales_supervisor')) {
        //             $query->where('kode_supervisor', $user->kode_supervisor);
        //         }

        //         if ($user->hasRole('sales')) {
        //             $query->where('kode_salesman', $user->kode_sales);
        //         }
        //     })
        //     ->where([
        //         ['batal', 0],
        //         ['kode_supervisor', '!=', 'OFFCE']
        //     ])
        //     ->select('nomor', DB::raw('convert(date, tanggal, 105) as tanggal'))
        //     ->orderBy('tanggal')
        //     ->get();
    }

    public function getFaktur($user)
    {
        $summary_faktur = SummaryFaktur::select(
            'summary_faktur.tanggal',
            'summary_faktur.model',
            'u.kode_sales',
            'pd.kode_spv',
            'pd.no_spk as nomor',
            'pd.nama_customer',
            'pd.no_pengajuan',
            DB::raw('if(isnull(pdu.cara_beli), pd.cara_beli, pdu.cara_beli) as jenispenjualan'),
            DB::raw('if(isnull(pdu.leasing), pd.leasing, pdu.leasing) as leasing'),
            DB::raw('if(isnull(pdu.jenis_leasing), pd.jenis_leasing, pdu.jenis_leasing) as jenis_leasing'),
            DB::raw('if(isnull(pdu.fleet), pd.fleet, pdu.fleet) as fleet'),
        )
            ->whereMonth('summary_faktur.tanggal', date('m'))
            ->whereYear('summary_faktur.tanggal', date('Y'))
            ->join('pengajuan_discount as pd', 'pd.no_spk', '=', 'summary_faktur.no_spk')
            ->leftJoin('pengajuan_discount_ulang as pdu', function ($join) {
                $join->on('pdu.pengajuan_discount_id', '=', 'pd.id')->where('pdu.aktif', 'Y');
            })
            ->join('users as u', 'u.username', '=', 'pd.username_pemohon')
            ->get();


        if ($user->hasRole('sales_supervisor')) {
            $summary_faktur = $summary_faktur->filter(function ($faktur) use ($user) {
                return $faktur->kode_spv == $user->kode_supervisor;
            });
        }

        if ($user->hasRole('sales')) {
            $summary_faktur = $summary_faktur->filter(function ($faktur) use ($user) {
                return $faktur->kode_sales == $user->kode_sales and $faktur->fleet != 'Fleet SPV';
            });
        }

        return $summary_faktur;

        // return DB::connection('sqlsrv')
        //     ->table('vw_faktur')
        //     ->whereMonth('tglappfakpol', date('m'))
        //     ->whereYear('tglappfakpol', date('Y'))
        //     ->where(function ($query) use ($user) {
        //         if ($user->hasRole('sales_supervisor')) {
        //             $query->where('kode_supervisor', $user->kode_supervisor);
        //         }

        //         if ($user->hasRole('sales')) {
        //             $query->where('kode_salesman', $user->kode_sales);
        //         }
        //     })
        //     ->select('nomor', 'namastnk')->get();
    }

    public function getFakturPeriode($tgl_awal, $tgl_akhir, $user)
    {
        $summary_faktur = SummaryFaktur::select(
            'pd.kode_spv',
            'pd.no_spk as nomor',
            'summary_faktur.tanggal',
            'summary_faktur.model',
            'u.kode_sales',
            'pd.cara_beli as jenispenjualan',
            'pd.no_pengajuan',
            'pd.leasing',
            'pd.jenis_leasing',
            DB::raw('if(isnull(pdu.fleet), pd.fleet, pdu.fleet) as fleet'),
        )
            ->whereDate('summary_faktur.tanggal', '>=', $tgl_awal)
            ->whereDate('summary_faktur.tanggal', '<=', $tgl_akhir)
            ->join('pengajuan_discount as pd', 'pd.no_spk', '=', 'summary_faktur.no_spk')
            ->leftJoin('pengajuan_discount_ulang as pdu', function ($join) {
                $join->on('pdu.pengajuan_discount_id', '=', 'pd.id')->where('pdu.aktif', 'Y');
            })
            ->join('users as u', 'u.username', '=', 'pd.username_pemohon')
            ->get();

        if ($user->hasRole('sales_supervisor')) {
            $summary_faktur = $summary_faktur->filter(function ($faktur) use ($user) {
                return $faktur->kode_spv == $user->kode_supervisor;
            });
        }

        if ($user->hasRole('sales')) {
            $summary_faktur = $summary_faktur->filter(function ($faktur) use ($user) {
                return $faktur->kode_sales == $user->kode_sales and $faktur->fleet != 'Fleet SPV';
            });
        }

        return $summary_faktur;

        // return DB::connection('sqlsrv')
        //     ->table('vw_faktur')
        //     ->whereDate('tglappfakpol', '>=', $tgl_awal)
        //     ->whereDate('tglappfakpol', '<=', $tgl_akhir)
        //     ->where(function ($query) use ($user) {
        //         if ($user->hasRole('sales_supervisor')) {
        //             $query->where('kode_supervisor', $user->kode_supervisor);
        //         }

        //         if ($user->hasRole('sales')) {
        //             $query->where('kode_salesman', $user->kode_sales);
        //         }
        //     })
        //     ->select('nomor', DB::raw('convert(date, tglappfakpol, 105) as tanggal'))
        //     ->orderBy('tglappfakpol')
        //     ->get();
    }

    public function getOutstanding($user)
    {
        return DB::connection('sqlsrv')
            ->table('vw_spkmonitoring')
            ->where('tglfakturnaik', '')
            ->where([
                ['tglserahbpkb', ''],
                ['pajaksendiri', 0],
                ['tanggal_gudangout', ''],
                ['kode_supervisor', '!=', 'OFFCE'],
            ])
            ->where(function ($query) use ($user) {
                if ($user->hasRole('sales_supervisor')) {
                    $query->where('kode_supervisor', $user->kode_supervisor);
                }

                if ($user->hasRole('sales')) {
                    $query->where('kode_salesman', $user->kode_sales);
                }
            })
            ->select('nomor', 'tanggal')->get();
    }

    public function getTargetMarketing($periode)
    {
        return TargetMarketing::where('periode', $periode)->sum('target');
    }

    public function getFakturPenjualan($jenis, $user)
    {
        $faktur = DB::connection('sqlsrv')
            ->table('vw_faktur')
            ->whereDate('tglappfakpol', '>=', date('Y-m') . '-01')
            // ->whereDate('tglappfakpol', '>=', '2020-01-01')
            // ->whereDate('tglappfakpol', '<=', '2020-01-31')
            ->where('jenispenjualan', $jenis)
            ->where(function ($query) use ($user) {
                if ($user->hasRole('sales_supervisor')) {
                    $query->where('kode_supervisor', $user->kode_supervisor);
                }

                if ($user->hasRole('sales')) {
                    $query->where('kode_salesman', $user->kode_sale);
                }
            });

        if ($jenis) {
            return $faktur->pluck('nomor');
        }
        return $faktur->count();
    }

    public function getAsuransi()
    {
        $asuransi = DB::connection('sqlsrv')
            ->table('vw_asuransipurnajual')
            ->select('nomor_pesanan as no_spk', 'kode_supervisor', 'kode_salesman')
            ->whereDate('tanggal', '>=', date('Y-m') . '-01')
            // ->whereNotIn('kode_supervisor', ['OFFCE', 'RIZAL'])
            ->where('batal', 0)
            ->get();

        $pengajuan_discount = PengajuanDiscount::select('no_spk')->whereIn('no_spk', $asuransi->pluck('no_spk'))->get();

        $asuransi = $asuransi->filter(function ($asu) use ($pengajuan_discount) {
            $is_match = count($pengajuan_discount->filter(function ($pd) use ($asu) {
                return $pd->no_spk = $asu->no_spk;
            }));
            if ($is_match) {
                return true;
            }
            return false;
        });

        return $asuransi;
    }

    public function getPengajuanDiskonJoinUlang($no_spk)
    {
        return PengajuanDiscount::leftJoin('pengajuan_discount_ulang as pdu', function ($join) {
            $join->on('pdu.pengajuan_discount_id', '=', 'pengajuan_discount.id')
                ->where('pdu.aktif', 'Y');
        })->select(
            'pengajuan_discount.no_spk',
            DB::raw('if(isnull(pdu.leasing), pengajuan_discount.leasing, pdu.leasing) as leasing'),
            DB::raw('if(isnull(pdu.jenis_leasing), pengajuan_discount.jenis_leasing, pdu.jenis_leasing) as jenis_leasing'),
            DB::raw('if(isnull(pdu.cara_beli), pengajuan_discount.cara_beli, pdu.cara_beli) as cara_beli'),
        )->whereIn('no_spk', $no_spk)->get();
    }

    public function getLeasing()
    {
        return DB::table('leasing')->where('aktif', 1)->pluck('nama_leasing', 'kode_leasing');
    }

    public function getLeads($user)
    {
        return Lead::leftJoin('users as spv', 'spv.id', '=', 'leads.spv_id')
            ->leftJoin('users as sales', 'sales.id', '=', 'leads.sales_id')
            ->where(function ($query) use ($user) {
                if ($user->hasRole('sales_supervisor')) {
                    $query->where('spv_id', $user->id);
                }

                if ($user->hasRole('sales')) {
                    $query->where('sales_id', $user->id);
                }
            })
            ->select(
                'sales.kode_sales',
                'spv.kode_supervisor',
                DB::raw('if(isnull(status), "Un-Leads", status) as status'),
                'model_kendaraan',
                'metode_pembayaran',
                'sumber_lead'
            )
            ->get();
    }
}
