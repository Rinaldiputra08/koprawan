<?php

namespace App\Services;

use App\Repositories\DashboardRepository;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    protected $repository;

    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function data()
    {
        $user = auth()->user();
        $prospek = $this->repository->getPengajuanDiskon($user);
        Cache::put('prospek', $prospek);
        $spk = $this->repository->getSpk($user);
        Cache::put('spk', $spk);
        $faktur = $this->repository->getFaktur($user);
        Cache::put('faktur', $faktur);

        return [
            'stat_prospek' => [
                'prospek' => $prospek->count(),
                'spk' => $spk->count(),
                'faktur' => $faktur->count()
            ]
        ];
    }

    public function detail($jenis)
    {
        $data = [];
        $user = auth()->user();
        if (Cache::has($jenis)) {
            $data = Cache::pull($jenis);
        } else {
            if ($jenis == 'prospek') {
                $data = $this->repository->getPengajuanDiskon($user);
            } elseif ($jenis == 'spk') {
                $data = $this->repository->getSpk($user);
            } elseif ($jenis == 'faktur') {
                $data = $this->repository->getFaktur($user);
            }
        }

        return [
            'detail' => $jenis,
            'data' => $data
        ];
    }

    public function komparasi()
    {
        $user = auth()->user();
        $label = [];
        $tgl_akhir = now();
        // $tgl_akhir = '2021-03-09';
        $bulan_akhir = date('n');
        $bulan_awal = $bulan_akhir - 6;
        $tahun_awal = date('Y');
        // $tahun_awal = 2021;
        if ($bulan_awal < 0) {
            $bulan_awal = 12 - abs($bulan_awal);
            $tahun_awal = date('Y') - 1;
            // $tahun_awal = 2021 - 1;
        }
        $bulan_awal = sprintf('%02s', $bulan_awal + 1);
        $tgl_awal = $tahun_awal . '-' . $bulan_awal . '-01';

        $label = collect(getListMonth($tgl_awal, $tgl_akhir))->map(function ($item) {
            $exp = explode('-', $item);
            return $exp[0] . '/' . substr($exp[1], 2);
        });

        $prospek = $this->repository->getPengajuanDiskonPeriode($tgl_awal, $tgl_akhir, $user)
            ->filter(function ($filter) {
                return (int) $filter->tgl >= 1 and (int) $filter->tgl <= date('d');
            })->countBy('tanggal');

        $spk = $this->repository->getSpkPeriode($tgl_awal, $tgl_akhir, $user)
            ->filter(function ($filter) {
                return (int) date('d', strtotime($filter->tanggal)) >= 1 and (int) date('d', strtotime($filter->tanggal)) <= date('d');
            })->mapWithKeys(function ($item, $key) {
                return [$key => [
                    'nomor' => $item->nomor,
                    'tanggal' => date('m/y', strtotime($item->tanggal))
                ]];
            })->countBy('tanggal');

        $faktur = $this->repository->getFakturPeriode($tgl_awal, $tgl_akhir, $user)
            ->filter(function ($filter) {
                return (int) date('d', strtotime($filter->tanggal)) >= 1 and (int) date('d', strtotime($filter->tanggal)) <= date('d');
            })->mapWithKeys(function ($item, $key) {
                return [$key => [
                    'nomor' => $item->nomor,
                    'tanggal' => date('m/y', strtotime($item->tanggal))
                ]];
            })->countBy('tanggal');

        return [
            'range' => $tgl_awal . ' s/d ' . $tgl_akhir,
            'label' => $label,
            'data' => [
                'prospek' => $prospek,
                'spk' => $spk,
                'faktur' => $faktur
            ]
        ];
    }

    public function outstanding()
    {
        $user = auth()->user();
        $outstanding = $this->repository->getOutstanding($user);
        $outstandingup = $outstanding->filter(function ($item) {
            return $item->tanggal < now()->addMonths(-3);
        })->count();
        $outstandingmin = $outstanding->filter(function ($item) {
            return $item->tanggal > now()->addMonths(-3) and date('Y-m', strtotime($item->tanggal)) != date('Y-m');
        })->count();
        $outstandingcurrent = $outstanding->filter(function ($item) {
            return date('Y-m', strtotime($item->tanggal)) == date('Y-m');
        })->count();

        return [
            // 'total' => $outstanding->count(),
            '-3 Bulan' => $outstandingmin,
            'Bulan Ini' => $outstandingcurrent,
            '+3 Bulan' => $outstandingup,
        ];
    }

    public function pencapaian()
    {
        $user = auth()->user();
        $target = $this->repository->getTargetMarketing(date('Ym'));
        if (Cache::has('faktur')) {
            $faktur = Cache::get('faktur');
        } else {
            $faktur = $this->repository->getFaktur($user);
        }

        $tunai = $faktur->filter(function ($faktur) use ($user) {
            return $faktur->jenispenjualan == 'TUNAI';
        })->count();
        $asuransi = $this->repository->getAsuransi()->count();

        $leasing = $this->repository->getLeasing()->mapWithKeys(function ($item, $key) use ($faktur) {
            return [$item => count($faktur->filter(function ($f) use ($item) {
                return $item == $f->leasing;
            }))];
        });

        $leasing = $leasing->put(
            'COP',
            count($faktur->filter(function ($f) {
                return $f->jenispenjualan == 'COP';
            }))
        );

        return [
            'marketing' => [
                'target' => $target,
                'faktur' => $faktur->count(),
                'rate' => ((int)$target) ? round(($faktur->count() / $target) * 100, 0) : 0
            ],
            'cara_beli' => [
                'tunai' => $tunai,
                'asuransi' => $asuransi,
                'rate' => ((int)$tunai) ? round(($asuransi / $tunai) * 100, 0) : 0
            ],
            'leasing' => $leasing
        ];
    }

    public function leads($jenis)
    {
        $user = auth()->user();
        $leads = $this->repository->getLeads($user);
        $data = [];
        if ($jenis == 'status') {
            $data = $leads->countBy('status');
        } elseif ($jenis == 'sumber') {
            $data = $leads->groupBy('sumber_lead')->mapWithKeys(function ($item, $key) {
                $count = $item->countBy('status');
                return [$key => [
                    'Un-Leads' => $count['Un-Leads'] ?? 0,
                    'Process' => $count['Process'] ?? 0,
                    'Loss' => $count['Loss'] ?? 0,
                    'SPK' => $count['SPK'] ?? 0
                ]];
            });
        } else if ($jenis == 'tim') {
            $data = $leads->groupBy('kode_supervisor')->mapWithKeys(function ($item, $key) {
                $count = $item->countBy('status');
                return [$key => [
                    'Process' => $count['Process'] ?? 0,
                    'Loss' => $count['Loss'] ?? 0,
                    'SPK' => $count['SPK'] ?? 0
                ]];
            });
            $data->pull('');
        }

        return [
            'jenis' => $jenis,
            'data' => $data,
        ];
    }
}
