<?php

namespace App\Http\Controllers\Pembelian;

use App\DataTables\PenerimaanProdukDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\PenerimaanProdukRequest;
use App\Models\Pembelian\PenerimaanProduk;
use App\Repositories\PenerimaanProdukRepository;
use App\Services\PenerimaanProdukService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanProdukController extends Controller
{
    protected $repository, $service;

    public function __construct(PenerimaanProdukRepository $repository, PenerimaanProdukService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(PenerimaanProdukDataTable $datatable)
    {
        return $datatable->render('pembelian.penerimaanproduk');
    }

    public function create(PenerimaanProduk $penerimaanProduk)
    {
        $supplier = $this->repository->getSupplier();
        return view('pembelian.penerimaanproduk-action', ['data' => $penerimaanProduk, 'supplier' => $supplier]);
    }

    public function store(PenerimaanProdukRequest $request)
    {
        DB::beginTransaction();
        try {
            if (!$request->qty) {
                throw new Exception('Belum memilih produk', 1);
            }

            if ($request->pemesanan) {
                $pemesanan = $this->repository->getPemesananById($request->nomor_pemesanan, 'nomor');
                if (!$pemesanan) {
                    throw new Exception('Data pemesanan tidak temukan', 1);
                }

                if ($pemesanan->penerimaan) {
                    throw new Exception('Data pemesanan sudah penerimaan', 1);
                }

                $request->pemesanan_id = $pemesanan->id;
                $request->supplier = $pemesanan->supplier_id;

                $produk_in_detail = $pemesanan->pemesananDetail->pluck('produk_id')->toArray();
                $list_produk_id = collect($request->qty)->filter(function ($item, $key) use ($produk_in_detail) {
                    return in_array($key, $produk_in_detail);
                })->keys()->toArray();

                $this->repository->updateStatusPenerimaanDetail($pemesanan->id, $list_produk_id);
                if (count($list_produk_id) == count($produk_in_detail)) {
                    $pemesanan->penerimaan = 1;
                    $pemesanan->save();
                }
            } else {
                $list_produk_id = array_keys($request->qty);
            }

            $produk = $this->repository->getListedProduk($list_produk_id)->keyBy('id');
            if (count($produk) == 0) {
                throw new Exception('Data produk tidak tersedia', 1);
            }

            $mapping_data = $this->service->mappingProdukDetail($request, $produk);

            $this->repository->store($request, $mapping_data['item_produk']);
            $this->repository->updateStokProduk($mapping_data['update_stok']);

            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th);
        }
    }

    public function show(PenerimaanProduk $penerimaanProduk)
    {
        $penerimaanProduk->load(['penerimaanDetail.produk', 'supplier']);
        return view('pembelian.penerimaanproduk-detail', ['data' => $penerimaanProduk]);
    }

    public function edit(PenerimaanProduk $penerimaanProduk)
    {
        $penerimaanProduk->load(['penerimaanDetail.produk', 'supplier']);
        return view('pembelian.penerimaanproduk-detail', ['data' => $penerimaanProduk]);
    }

    public function update(Request $request, PenerimaanProduk $penerimaanProduk)
    {
        $request->validate([
            'nomor_tagihan' => 'required',
            'tanggal_tagihan' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $penerimaanProduk->nomor_tagihan = $request->nomor_tagihan;
            $penerimaanProduk->tanggal_tagihan = $request->tanggal_tagihan;
            $penerimaanProduk->save();

            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th);
        }
    }

    public function batal(Request $request, PenerimaanProduk $penerimaanProduk)
    {
        DB::beginTransaction();
        try {
            $penerimaanProduk->load('penerimaanDetail.produk');
            if (!$penerimaanProduk) {
                throw new Exception('Data pemesanan tidak ditemukan', 1);
            }

            $penerimaanProduk = $this->repository->batal($request, $penerimaanProduk);
            $penerimaanDetail = $penerimaanProduk->penerimaanDetail->keyBy('produk_id');
            $list_produk_id = $penerimaanDetail->keys()->toArray();
            $produk = $this->repository->getListedProduk($list_produk_id);
            if ($penerimaanProduk->pemesanan_produk_id) {
                $this->repository->updateStatusPenerimaanDetail($penerimaanProduk->pemesanan_produk_id, $list_produk_id, 0);
                $produk_belum_penerimaan = $penerimaanProduk->pemesanan->pemesananDetail()->selesaiPenerimaan(false)->count();

                if ($produk_belum_penerimaan > 0) {
                    $penerimaanProduk->pemesanan->penerimaan = 0;
                    $penerimaanProduk->pemesanan->save();
                }
            }

            $update_stok = [];
            foreach ($produk as $item) {
                if ($item->stock_free < $penerimaanDetail[$item->id]->qty) {
                    throw new Exception("Gagal, Barang sudah terjual, Sisa Stok produk {$item->kode}: {$item->stock_free}, pembatalan {$penerimaanDetail[$item->id]->qty}", 1);
                }

                $update_stok[] = [
                    'id' => $item->id,
                    'stock_free' => $item->stock_free - $penerimaanDetail[$item->id]->qty,
                    'stock_fisik' => $item->stock_fisik - $penerimaanDetail[$item->id]->qty,
                ];
            }

            $this->repository->updateStokProduk($update_stok);

            DB::commit();
            return responseMessage('success', 'Data berhasil dibatalkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th);
        }
    }

    public function find(Request $request)
    {
        $data = $this->repository->getPenerimaan($request->search);

        if ($request->has('id')) {
            $data = $this->repository->getPenerimaanById($request->id);
            if (!$data) {
                return responseMessage('error', 'Data penerimaan tidak ditemukan');
            }
        }

        if ($request->has('search') or $request->has('id')) {
            return $this->service->mappingListPenerimaan($data);
        }

        return view('pembelian.penerimaanproduk-find', compact('data'));
    }
}
