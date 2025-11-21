<?php

namespace App\Http\Controllers\MasterData;

use App\DataTables\ProdukDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProdukRequest;
use App\Models\MasterData\Produk;
use App\Repositories\ProdukRepository;
use App\Services\ProdukService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProdukController extends Controller
{
    private $repository, $service;

    public function __construct(ProdukRepository $repository, ProdukService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(ProdukDataTable $datatable)
    {
        return $datatable->render('master-data.produk');
    }

    public function create(Produk $produk)
    {
        return view('master-data.produk-action', array_merge($this->repository->getKategoriMerek(), ['data' => $produk]));
    }

    public function store(ProdukRequest $request)
    {
        DB::beginTransaction();
        try {
            $produk = $this->repository->store($request);
            if ($request->has('upload_foto')) {
                $this->repository->uploadFoto($request->upload_foto, $produk);
            }
            if ($request->has('foto_thumbnail')) {
                $this->repository->setFotoThumbnail($request, $produk);
            }
            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th->getMessage());
        }
    }

    public function show(Produk $produk)
    {
        $produk->load('foto');
        return view('master-data.produk-detail', ['data' => $produk]);
    }

    public function edit(Produk $produk)
    {
        $produk->load('foto');
        return view('master-data.produk-action', array_merge($this->repository->getKategoriMerekEdit(), ['data' => $produk]));
    }

    public function update(ProdukRequest $request, Produk $produk)
    {
        DB::beginTransaction();
        try {
            $produk = $this->repository->update($request, $produk);
            if ($request->has('remove_upload_foto')) {
                $this->repository->deleteFoto($request->remove_upload_foto, $produk);
            }
            if ($request->has('upload_foto')) {
                $this->repository->uploadFoto($request->upload_foto, $produk);
            }
            if ($request->has('foto_thumbnail')) {
                $this->repository->setFotoThumbnail($request, $produk);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th->getMessage());
        }

        return responseMessage('success', 'Data berhasil diperbaharui');
    }

    public function find(Request $request)
    {
        $data = $this->repository->getProduk($request->search);
        if ($request->has('kode')) {
            $data = $this->repository->getProdukById($request->kode, 'kode');
            if (!$data) return null;
        }

        if ($request->has('search') or $request->has('kode')) {
            return $this->service->mappingListProduk($data);
        }

        return view('master-data.produk-find', compact('data'));
    }

    public function destroy($id)
    {
        //
    }
}
