<?php

namespace App\Http\Controllers\Gudang;

use App\DataTables\BeritaAcaraGudangDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\BeritaAcaraGudangRequest;
use App\Models\Gudang\BeritaAcaraGudang;
use App\Models\Gudang\BeritaAcaraGudangDetail;
use App\Models\MasterData\Produk;
use App\Repositories\BeritaAcaraGudangRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeritaAcaraGudangController extends Controller
{
    private $repository;

    public function __construct(BeritaAcaraGudangRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(BeritaAcaraGudangDataTable $datatable)
    {
        return $datatable->render('gudang.berita_acara_gudang');
    }

    public function create(BeritaAcaraGudang $gudang)
    {

        return view('gudang.berita_acara_gudang_action', [
            'data' => $gudang,
        ]);
    }

    public function store(BeritaAcaraGudangRequest $request)
    {
        DB::beginTransaction();
        try {

            if (!$request->qty) {
                throw new Exception('Belum memilih produk', 1);
            }

            $produk = Produk::whereIn('id', array_keys($request->qty))->get()->keyBy('id');
            if (count($produk) == 0) {
                throw new Exception('Data produk tidak tersedia', 1);
            }

            $item_produk = [];
            $update_stok = [];
            foreach ($produk as $produk_id => $item) {
                $qty = (int)str_replace('.', '', $request->qty[$produk_id]);

                $item_produk[$produk_id] = new BeritaAcaraGudangDetail([
                    'produk_id' => $produk_id,
                    'qty' => $qty,
                    'keterangan' => $request->keterangan_produk[$produk_id],
                ]);

                if ($request->jenis == 'Keluar') {

                    if ($item->stock_free < $qty || $item->stock_fisik < $qty) {
                        throw new Exception("Gagal, qty melebihi stock produk {$item->kode}: {$item->stock_free}", 1);
                    }
                }


                if ($request->jenis == 'Keluar') {
                    $qty *= -1;
                }

                $update_stok[] = [
                    'id' => $item->id,
                    'stock_free' => $item->stock_free  + $qty,
                    'stock_fisik' => $item->stock_fisik + $qty,
                ];
            }
            $this->repository->store($request, $item_produk);
            $this->repository->updateStokProduk($update_stok);

            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th);
        }
    }

    public function show(BeritaAcaraGudang $beritaAcaraGudang)
    {
        $beritaAcaraGudang->load(['beritaAcaraGudangDetail.produk']);
        return view('gudang.beritaacaragudang-detail', ['data' => $beritaAcaraGudang]);
    }

    public function batal(Request $request, BeritaAcaraGudang $beritaAcaraGudang)
    {
        DB::beginTransaction();
        try {
            $beritaAcaraGudang->load('beritaAcaraGudangDetail.produk');
            if (!$beritaAcaraGudang) {
                throw new Exception('Data berita acara tidak ditemukan', 1);
            }

            $beritaAcaraGudang = $this->repository->batal($request, $beritaAcaraGudang);
            $beritaAcaraGudangDetail = $beritaAcaraGudang->beritaAcaragudangDetail->keyBy('produk_id');
            $list_produk_id = $beritaAcaraGudangDetail->keys()->toArray();
            $produk = $this->repository->getListedProduk($list_produk_id);

            $update_stok = [];
            foreach ($produk as $item) {
                $jumlah = $beritaAcaraGudangDetail[$item->id]->qty;

                if ($beritaAcaraGudang->jenis == 'Masuk') {

                    if ($item->stock_free < $jumlah || $item->stock_fisik < $jumlah) {
                        throw new Exception("Gagal, Barang sudah terjual, Sisa Stok produk {$item->kode}: {$item->stock_free}, pembatalan {$beritaAcaraGudangDetail[$item->id]->qty}", 1);
                    }
                }


                if ($beritaAcaraGudang->jenis == 'Masuk') {
                    $jumlah *= -1;
                }

                $update_stok[] = [
                    'id' => $item->id,
                    'stock_free' => $item->stock_free  + $jumlah,
                    'stock_fisik' => $item->stock_fisik + $jumlah,
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
}
