<?php

namespace App\Http\Controllers\MasterData;

use App\DataTables\KaryawanDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\KaryawanRequest;
use App\Models\MasterData\Karyawan;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KaryawanController extends Controller
{
    private $uploadService;
    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }
    public function index(KaryawanDataTable $datatable)
    {
        return $datatable->render('master-data.karyawan');
    }

    public function create(Karyawan $karyawan)
    {
        return view('master-data.karyawan-action', ['data' => $karyawan]);
    }

    public function store(KaryawanRequest $request)
    {
        DB::beginTransaction();
        try {

            if ($request->upload_foto) {
                $foto = $this->uploadService->uploadFromBase64($request->upload_foto, 'karyawan', time() . rand());
            }
            $karyawan = Karyawan::create([
                'uuid' => Str::uuid(),
                'nik' => $request->nik,
                'nama' => $request->nama,
                'divisi' => $request->divisi,
                'foto' => $foto
            ]);

            $karyawan->limit()->create([
                'nominal' => str_replace('.', '', $request->limit),
                'periode' => getCurrentPeriode(),
            ]);

            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th);
        }
    }

    public function edit(Karyawan $karyawan)
    {
        return view('master-data.karyawan-action', ['data' => $karyawan]);
    }

    public function update(KaryawanRequest $request, Karyawan $karyawan)
    {
        DB::beginTransaction();
        try {

            if ($request->upload_foto) {
                if ($karyawan->foto) {
                    $karyawan->foto()->delete();
                    $this->uploadService->deleteFoto($karyawan->foto, 'public/images/karyawan/');
                }
                $foto = $this->uploadService->uploadFromBase64($request->upload_foto, 'karyawan', time() . rand());
            }
            $karyawan->nik = $request->nik;
            $karyawan->nama = $request->nama;
            $karyawan->divisi = $request->divisi;
            $karyawan->foto = $foto;
            $karyawan->aktif = $request->aktif;
            $karyawan->limit()->update([
                'nominal' => str_replace('.', '', $request->limit),
            ]);

            $karyawan->save();

            DB::commit();
            return responseMessage('success', 'Data berhasil diperbaharui');
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th);
        }
    }
}
