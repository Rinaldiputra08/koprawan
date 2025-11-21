<?php

namespace App\Http\Controllers\Penjualan;

use App\DataTables\PenjualanLangsungDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\PenjualanLangsungRequest;
use App\Models\MasterData\Produk;
use App\Models\Penjualan\PemakaianVoucher;
use App\Models\Penjualan\Penjualan;
use App\Models\Penjualan\PenjualanDetail;
use App\Models\Titipan\Titipan;
use App\Repositories\PenjualanLangsungRepository;
use App\Services\GenerateCodeService;
use App\Services\PenjualanLangsungService;
use DateTime;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ReflectionClass;

class PenjualanLangsungController extends Controller
{

    protected $repository, $service;

    public function __construct(PenjualanLangsungRepository $repository, PenjualanLangsungService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(PenjualanLangsungDataTable $datatable)
    {
        return $datatable->render('penjualan.penjualanlangsung');
    }

    public function create(Penjualan $penjualan)
    {
        return view('penjualan.penjualanlangsung-action', ['data' => $penjualan]);
    }

    public function scanQr($qrcode)
    {
        $qrservice = new GenerateCodeService;
        $nik = $qrservice->decryptKeyBased($qrcode);
        $data = $this->repository->getDataKaryawan($nik);
        $pemakaian_voucher = $data->pemakaianVoucher;
        $voucher_umum = $this->repository->getVoucherUmum();
        $vouchers = $this->service->dataVoucher($data, $voucher_umum, $pemakaian_voucher);

        return view('penjualan.penjualanlangsung-pembeli', compact('data', 'vouchers'));
    }

    public function findProduk(Request $request)
    {
        $jenis =  $request->jenis ?? 'koperasi';

        if ($jenis == 'koperasi') {
            $data = $this->repository->getProduk($request->search);
        } else {
            $data = $this->repository->getProdukTitipan($request->search)->map(function ($item) {
                return (object) [
                    'id' => $item->id,
                    'kode' => '-',
                    'nama' => $item->nama,
                    'harga_jual_formatted' => $item->harga_jual_formatted,
                    'diskon' => 0,
                    'stock_free' => $item->stock_free,
                ];
            });
        }

        if ($request->has('kode')) {
            $data = $this->repository->getProdukById($request->kode, 'kode');
            if (!$data) return null;
        }

        if ($request->has('search') or $request->has('kode') or $request->has('jenis')) {
            return $this->service->mappingListProduk($data, $jenis);
        }

        return view('penjualan.penjualanlangsung-find-produk', compact('data'));
    }

    public function store(PenjualanLangsungRequest $request)
    {
        // dd($request->qty);
        DB::beginTransaction();
        try {
            $karyawan = $this->repository->getDataKaryawan($request->karyawan);
            if (!$karyawan) {
                throw new Exception('Data karyawan tidak ditemukan', 1);
            }
            $request->karyawan_id = $karyawan->id;

            if (!$request->qty) {
                throw new Exception('Belum memilih produk', 1);
            }

            $produk = $this->repository->getListedProduk(array_keys($request->qty['koperasi']))->keyBy('id');
            if (count($produk) == 0) {
                throw new Exception('Data produk tidak tersedia', 1);
            }

            $titipan = [];
            if (isset($request->qty['titipan'])) {
                $titipan = $this->repository->getListTitipan(array_keys($request->qty['titipan']))->keyBy('id');
                if (count($titipan) == 0) {
                    throw new Exception('Data produk tidak tersedia', 1);
                }
            }

            $listproduk = [
                'koperasi' => $produk,
                'titipan' => $titipan,
            ];

            // dd($listproduk);

            $item_produk = [];
            $total_diskon = 0;
            $total_harga = 0;
            $update_stok = [];

            foreach ($listproduk as $jenis => $produk) {

                foreach ($produk as $produk_id => $item) {
                    $qty = (int)str_replace('.', '', $request->qty[$jenis][$produk_id]);
                    $total_harga_produk = $qty * $item->harga_jual;
                    $diskon_produk =  $qty * ($item->diskon->nominal ?? 0);
                    $total_diskon += $diskon_produk;
                    $total_harga += $total_harga_produk;
                    // prepare data for penjualan detial
                    $item_produk[] = new PenjualanDetail([
                        'produk_id' => $produk_id,
                        'produk_type' => get_class($item),
                        'harga' => $item->harga_jual,
                        'qty' => $qty,
                        'hpp' => $item->hpp ?? 0,
                        'total_harga' => $total_harga_produk,
                        'diskon_id' => $item->diskon->id ?? null,
                        'nominal_diskon' => $item->diskon->nominal ?? 0,
                        'grand_total' => $total_harga_produk - $diskon_produk,
                    ]);

                    // validasi stock
                    if ($item->stock_free < $qty || $item->stock_fisik < $qty) {
                        throw new Exception("Gagal, qty melebihi stock produk {$item->kode}: {$item->stock_free}", 1);
                    }

                    // prepre data for update stock
                    $update_stok[] = [
                        'id' => $item->id,
                        'stock_free' => $item->stock_free  - $qty,
                        'stock_fisik' => $item->stock_fisik - $qty,
                    ];
                }
            }


            $request->total_harga = $total_harga;
            $request->diskon = $total_diskon;
            $request->grand_total = $total_harga - $total_diskon;
            // prepare data voucher if present
            if ($request->voucher) {
                $trans_voucher = $this->service->transVoucher($request, $this->repository);
            }
            if ($request->grand_total < 0) {
                $request->grand_total = 0;
            }
            // store penjualan
            $penjualan = $this->repository->store($request);
            // store voucher if present
            if (isset($trans_voucher)) {
                $penjualan->voucher()->saveMany($trans_voucher);
            }
            // store penjualan detail
            $penjualan->penjualanDetail()->saveMany($item_produk);

            // update stock product
            $this->repository->updateStokProduk($update_stok);
            // create or update piutang
            $piutang = $karyawan->piutang;
            $this->repository->upsertPiutang($penjualan, $piutang);

            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th);
        }
    }

    public function show(Penjualan $penjualan_langsung)
    {
        $penjualan_langsung->load(['karyawan', 'penjualanDetail.produk', 'voucher']);
        return view('penjualan.penjualanlangsung-detail', ['data' => $penjualan_langsung]);
    }


    public function batal(Request $request, Penjualan $penjualanLangsung)
    {
        DB::beginTransaction();
        try {
            $penjualanLangsung->load([
                'penjualanDetail.produk',
                'karyawan.piutang' => function ($query) use ($penjualanLangsung) {
                    $tanggal_transaksi = new DateTime($penjualanLangsung->tanggal);
                    $periode = $tanggal_transaksi->format('Ym');
                    if ($tanggal_transaksi->format('d') > 20) {
                        $periode += 1;
                    }
                    $query->where('periode', $periode);
                }
            ]);
            if (!$penjualanLangsung) {
                throw new Exception('Data penjualan tidak ditemukan', 1);
            }

            $penjualanLangsung = $this->repository->batal($request, $penjualanLangsung);
            $penjualanDetail = $penjualanLangsung->penjualanDetail->keyBy('produk_id');
            $list_produk_id = $penjualanDetail->keys()->toArray();
            $produk = $this->repository->getListedProduk($list_produk_id);

            $update_stok = [];
            foreach ($produk as $item) {
                $update_stok[] = [
                    'id' => $item->id,
                    'stock_free' => $item->stock_free + $penjualanDetail[$item->id]->qty,
                    'stock_fisik' => $item->stock_fisik + $penjualanDetail[$item->id]->qty,
                ];
            }

            $piutang = $penjualanLangsung->karyawan->piutang;
            $piutang->nominal -= $penjualanLangsung->grand_total;
            $piutang->save();


            $this->repository->updateStokProduk($update_stok);

            DB::commit();
            return responseMessage('success', 'Data berhasil dibatalkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th);
        }
    }
}
