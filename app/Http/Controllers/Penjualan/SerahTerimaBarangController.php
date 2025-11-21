<?php

namespace App\Http\Controllers\Penjualan;

use DateTime;
use Exception;

use Illuminate\Http\Request;
use App\Models\MasterData\Produk;
use Illuminate\Support\Facades\DB;
use App\Models\Penjualan\Penjualan;
use App\Http\Controllers\Controller;
use App\Models\SerahTerimaBarangDetail;
use App\Services\SerahTerimaBarangService;
use App\Models\Penjualan\SerahTerimaBarang;
use App\DataTables\SerahTerimaBarangDataTable;
use App\Http\Requests\SerahTerimaBarangRequest;
use App\Repositories\SerahTerimaBarangRepository;

class SerahTerimaBarangController extends Controller
{
    protected $repository, $service;

    public function __construct(SerahTerimaBarangRepository $repository, SerahTerimaBarangService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }
    
    public function index(SerahTerimaBarangDataTable $datatable)
    {       
        return $datatable->render('penjualan.serah-terima-barang');
    }

    public function create(SerahTerimaBarang $serah_terima_barang)
    {

        return view('penjualan.serah_terima_barang_action', [
            'data' => $serah_terima_barang,
        ]);
    }

    public function dataPenjualan($id)
    {
        return Penjualan::with('penjualanDetail.produk', 'karyawan')->where('id', $id)->first();
    }

    public function findPenjualan(Request $request)
    {
        $data = $this->repository->getPenjualan($request->search);
        
        if ($request->has('id')) {
            $data = $this->repository->getPenjualanById($request->id);
            if (!$data) {
                return responseMessage('error', 'Data serah terima tidak ditemukan');
            }
        }

        if($request->has('search') or $request->has('id')) {
            return $this->service->mappingListSerahTerima($data);
        }      

        return view('penjualan.serah-terima-find-penjualan', compact('data'));

    }

    public function store(SerahTerimaBarangRequest $request)
    {
        DB::beginTransaction();
        try{
            $penjualan = Penjualan::with('penjualanDetail.produk', 'karyawan')->where('nomor', $request->nomor_penjualan)->first();
            if (!$penjualan) {
                throw new Exception('Belum memilih list penjualan', 1);
            }

            $request->penjualan_id = $penjualan->id;
            $request->karyawan_id = $penjualan->karyawan->id;
            $item_penjualan = [];

            foreach ($penjualan->penjualanDetail as $penjualan_id => $item) {
                $item_penjualan[$penjualan_id] = new SerahTerimaBarangDetail([
                    'serah_terima_id' => $item->serah_terima_id,
                    'produk_id' => $item->produk_id,
                    'qty' => $item->qty,
                    'harga' => $item->harga,
                    'diskon' => $penjualan->diskon,
                    'grand_total' => ($item->harga * $item->qty) - $item->penjualan_diskon,
                ]);
                
            }

            $penjualan = $this->repository->store($request, $item_penjualan);
            $penjualan->serahTerimaDetail()->saveMany($item_penjualan);

            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th);
        }
    }

    public function show(SerahTerimaBarang $serah_terima_barang)
    {
        $serah_terima_barang->load(['karyawan', 'serahTerimaDetail.produk']);
        return view('penjualan.serahterima-detail', ['data' => $serah_terima_barang]);
    }


}