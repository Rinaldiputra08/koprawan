<?php

namespace App\Http\Controllers\MasterData;

use App\DataTables\LimitKaryawanDataTable;
use App\Http\Controllers\Controller;
use App\Models\MasterData\Karyawan;
use App\Models\MasterData\LimitKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mavinoo\Batch\BatchFacade;

class LimitKaryawanController extends Controller
{
    public function index(LimitKaryawanDataTable $dataTable)
    {
        $data = LimitKaryawan::orderBy('periode', 'DESC')->first();
        return $dataTable->render('master-data.limitkaryawan', compact('data'));
    }


    public function listKaryawan(Request $request, $divisi)
    {
        $data = Karyawan::with(['limit' => function ($query) use ($request) {
            if ($request->periode) {
                $query->where('periode', dateFormat('01-' . $request->periode, 'Ym'));
            } else {
                $query->latest('periode');
            }
        }])->where('divisi', $divisi)->active()->get();

        if ($request->periode) {
            $jenis = 'edit';
        } else {
            $jenis = 'tambah';
        }
        return view('master-data.limitkaryawan-list', compact('data', 'jenis'));
    }

    public function create()
    {
        return view('master-data.limitkaryawan-action');
    }

    public function store(Request $request)
    {

        $request->validate([
            'periode' => 'required',
        ]);
        DB::beginTransaction();
        try {

            $periode = dateFormat('01-' . $request->periode, 'Ym',);
            $cekExist = LimitKaryawan::whereHas('karyawan', function ($query) use ($request) {
                $query->where('divisi', $request->divisi);
            })->where('periode', $periode)->first();
            if ($cekExist) {
                return responseMessage('error', 'Data sudah ada');
            }

            $insert_all = [];
            foreach ($request->limit as $karyawan_id => $limit) {
                $insert_all[] = [
                    'karyawan_id' => $karyawan_id,
                    'nominal' => str_replace('.', '', $limit),
                    'periode' => $periode
                ];
            }

            LimitKaryawan::insert($insert_all);

            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th);
        }
    }

    public function filter()
    {
        $tgl = LimitKaryawan::orderByDesc('periode')->groupBy('periode')->get();
        return view('master-data.limitkaryawan-filter', compact('tgl'));
    }

    public function show($periode)
    {
        $data = LimitKaryawan::with('karyawan')->where('periode', $periode)->orderBy('periode')->get();
        $data = Karyawan::with(['limit' => function ($query) use ($periode) {
            $query->where('periode', $periode);
        }])->active()->get();
        $url = route('master-data.limit-karyawan.update', $periode);
        return view('master-data.limitkaryawan-action', compact('data', 'periode', 'url'));
    }

    public function update(Request $request, $periode)
    {
        DB::beginTransaction();
        try {
            $update = [];
            foreach ($request->limit as $id => $limit) {
                $update[] = [
                    'id' => $id,
                    'nominal' => str_replace('.', '', $limit),
                ];
            }

            BatchFacade::update(new LimitKaryawan(), $update, 'id');

            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th);
        }
    }
}
