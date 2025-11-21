<?php

namespace App\Http\Controllers\Pembelian;

use App\DataTables\PemesananProdukDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\PemesananProdukRequest;
use App\Models\MasterData\Produk;
use App\Models\Pembelian\PemesananProduk;
use App\Models\Pembelian\PemesananProdukDetail;
use App\Repositories\PemesananProdukRepository;
use App\Services\PemesananProdukService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemesananProdukController extends Controller
{
    private $repository, $service;

    public function __construct(PemesananProdukRepository $repository, PemesananProdukService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(PemesananProdukDataTable $datatable)
    {
        return $datatable->render('pembelian.pemesananproduk');
    }

    public function create(PemesananProduk $pemesananProduk)
    {
        $supplier = $this->repository->getSupplier();
        return view('pembelian.pemesananproduk-action', [
            'data' => $pemesananProduk,
            'supplier' => $supplier
        ]);
    }

    public function find(Request $request)
    {
        $data = $this->repository->getPemesanan($request->search, $request->has('active'));

        if ($request->has('id')) {
            $data = $this->repository->getPemesananById($request->id);
            if (!$data) {
                return responseMessage('error', 'Data pemesanan tidak ditemukan');
            }

            if ($request->has('active')) {
                $data->pemesananDetail = $data->pemesananDetail->where('penerimaan', 0)->values();
            }
        }

        if ($request->has('search') or $request->has('id')) {
            return $this->service->mappingListPemesanan($data);
        }

        return view('pembelian.pemesananproduk-find', compact('data'));
    }

    public function store(PemesananProdukRequest $request)
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
                $qty = str_replace('.', '', $request->qty[$produk_id]);
                $diskon = str_replace('.', '', $request->nominal_diskon[$produk_id]);

                $item_produk[$produk_id] = new PemesananProdukDetail([
                    'produk_id' => $produk_id,
                    'qty' => $qty,
                    'harga' => $item->harga_beli,
                    'diskon' => $diskon,
                    'sub_total' => ($item->harga_beli - $diskon) * $qty
                ]);

                $update_stok[] = [
                    'id' => $produk_id,
                    'stock_fisik' => $item->stock_fisik + $qty,
                    'stock_free' => $item->stock_free + $qty
                ];
            }

            $this->repository->store($request, $item_produk);

            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th);
        }
    }

    public function show(PemesananProduk $pemesananProduk)
    {
        $pemesananProduk->load(['pemesananDetail.produk', 'supplier'])
            ->loadCount(['pemesananDetail' => function ($query) {
                $query->selesaiPenerimaan();
            }]);
        return view('pembelian.pemesananproduk-detail', ['data' => $pemesananProduk]);
    }

    public function batal(Request $request, PemesananProduk $pemesananProduk)
    {
        DB::beginTransaction();
        try {
            $pemesananProduk->load(['penerimaan', 'pemesananDetail.produk']);
            if (!$pemesananProduk) {
                throw new Exception('Data pemesanan tidak ditemukan', 1);
            }

            $pemesananProduk = $this->repository->batal($request, $pemesananProduk);

            DB::commit();
            return responseMessage('success', 'Data berhasil dibatalkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th->getMessage());
        }
    }
}
