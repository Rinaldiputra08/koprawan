<?php

namespace App\Http\Controllers\MasterData;

use App\DataTables\KategoriDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\KategoriRequest;
use App\Models\MasterData\Kategori;
use App\Repositories\KategoriRepository;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    private $repository, $uploadService;
    public function __construct(KategoriRepository $repository, UploadService $uploadService)
    {
        $this->repository = $repository;
        $this->uploadService = $uploadService;
    }

    public function index(KategoriDataTable $datatable)
    {
        return $datatable->render('master-data.kategori');
    }

    public function show()
    {
    }

    public function create(Kategori $kategori)
    {
        return view('master-data.kategori-action', ['data' => $kategori]);
    }

    public function edit(Kategori $kategori)
    {
        return view('master-data.kategori-action', ['data' => $kategori]);
    }

    public function update(KategoriRequest $request, Kategori $kategori)
    {
        DB::beginTransaction();
        try {
            $kategori->nama = $request->nama;
            $kategori->aktif = $request->aktif;
            $kategori->save();
            if ($request->hasFile('foto')) {
                if ($kategori->foto) {
                    $kategori->foto()->delete();
                    $this->uploadService->deleteFoto($kategori->foto->nama_file, 'public/images/kategori-produk/');
                }
                $foto = $this->uploadService->uploadFoto($request->file('foto'), 'kategori-produk');
                $kategori->foto()->create(['nama_file' => $foto]);
            }
            DB::commit();
            return responseMessage('success', 'Data berhasil diperbaharui');
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th->getMessage());
        }
    }

    public function store(KategoriRequest $request)
    {
        DB::beginTransaction();
        try {
            $kategori = Kategori::create([
                'nama' => $request->nama,
                'user_id' => $request->user()->id
            ]);

            if ($request->hasFile('foto')) {
                $foto = $this->uploadService->uploadFoto($request->file('foto'), 'produk-titipan');
                $kategori->foto()->create(['nama_file' => $foto]);
            }
            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th->getMessage());
        }
    }
}
