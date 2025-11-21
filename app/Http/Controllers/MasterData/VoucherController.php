<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\VoucherDataTable;
use App\Http\Requests\VoucherRequest;
use App\Models\MasterData\Karyawan;
use App\Models\MasterData\Produk;
use App\Models\MasterData\Voucher;
use App\Models\MasterData\VoucherKriteria;
use App\Models\MasterData\VoucherUser;
use App\Models\User;
use App\Repositories\VoucherRepository;
use App\Services\VoucherService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    protected $service, $repository;

    public function __construct(VoucherService $service, VoucherRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }
    public function index(VoucherDataTable $datatable)
    {

        return $datatable->render('master-data.voucher');
    }
    public function create(Voucher $voucher)
    {
        $kriteria = VoucherKriteria::all();
        return view('master-data.voucher-action', ['data' => $voucher, 'kriteria' => $kriteria]);
    }

    public function store(VoucherRequest $request)
    {
        DB::beginTransaction();
        try {
            $voucher = $this->repository->store($request);
            if ($request->has_kriteria) {
                $kriteria = $this->repository->getKriteria($request->kriteria);
                $list = [];
                foreach ($kriteria as $item) {
                    $list[$item->nama] = $item->id;
                }
                $voucher->kriteria()->sync(array_values($list));
            }
            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th);
        }
    }

    public function edit(Voucher $voucher)
    {
        $voucher->load('kriteria');
        $list_kriteria = $voucher->kriteria->pluck('id')->toArray();
        $kriteria = VoucherKriteria::all();
        return view('master-data.voucher-action', ['data' => $voucher, 'list_kriteria' => $list_kriteria, 'kriteria' => $kriteria]);
    }


    public function update(VoucherRequest $request, Voucher $voucher)
    {
        $voucher->load(['kriteria', 'penerimaVoucher']);
        $voucher->nama = $request->nama;
        $voucher->ketentuan = $request->has_kriteria;
        $voucher->tanggal_awal = $request->tanggal_awal . " " . $request->jam_awal . ':' . $request->menit_awal;
        $voucher->tanggal_akhir = $request->tanggal_akhir . " " . $request->jam_akhir . ':' . $request->menit_akhir;
        $voucher->nominal = $request->nominal;
        $voucher->jenis = $request->jenis;
        if ($request->has_kriteria) {
            $kriteria = $this->repository->getKriteria($request->kriteria);
            $list = [];
            foreach ($kriteria as $item) {
                $list[$item->nama] = $item->id;
            }
            $voucher->kriteria()->sync(array_values($list));
        } else {
            $voucher->kriteria()->detach($voucher->kriteria->pluck('id')->toArray());
        }

        if ($voucher->penerimaVoucher->count() and $request->jenis == 'Voucher umum') {
            $voucher->penerimaVoucher()->detach($voucher->penerimaVoucher->pluck('id')->toArray());
        }

        $voucher->save();

        return responseMessage();
    }

    public function pilihUser(Voucher $voucher)
    {
        $periode = getCurrentPeriode();
        $voucher->load([
            'penerimaVoucher.piutang' => function ($query)
            use ($periode) {
                $query->where('periode', $periode);
            }
        ]);
        return view('master-data.voucher-pilih-user', ['data' => $voucher,]);
    }
    public function findUser(Request $request)
    {
        $periode = getCurrentPeriode();
        $data = Karyawan::with(['piutang' => function ($query)
        use ($periode) {
            $query->where('periode', $periode);
        }])->where(function ($query) use ($request) {
            if ($request->has('search')) {
                $query->where('nik', 'like', "%{$request->search}%")
                    ->orWhere('nama', 'like', "%{$request->search}%");
            }
        })->active()->limit(10)->get();

        if ($request->has('search')) {
            return $this->service->mappingListUser($data);
        }

        return view('master-data.voucher-find-user', compact('data'));
    }

    public function storePemakai(Voucher $voucher, Request $request)
    {
        try {
            $voucher->penerimaVoucher()->sync($request->karyawan);

            return responseMessage();
        } catch (\Throwable $th) {
            return responseMessage('error', $th);
        }
    }
}
