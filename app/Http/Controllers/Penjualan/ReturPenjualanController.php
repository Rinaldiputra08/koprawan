<?php

namespace App\Http\Controllers\Penjualan;

use App\DataTables\ReturDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReturRequest;
use App\Models\Penjualan\Penjualan;
use App\Models\Penjualan\Retur;
use App\Repositories\ReturPenjualanRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturPenjualanController extends Controller
{
    protected $repository, $service;

    public function __construct(ReturPenjualanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(ReturDataTable $datatable)
    {
        return $datatable->render('penjualan.retur');
    }

    public function create(Retur $retur)
    {
        return view('penjualan.retur-action', ['data' => $retur]);
    }

    public function findTransaksi(Request $request)
    {
        $data = $this->repository->getTrans($request->search);
        if ($request->has('nomor')) {
            $data = $this->repository->getTransById($request->nomor, 'nomor');
            if (!$data) return null;
        }
        if ($request->has('search') or $request->has('nomor')) {
            if ($data instanceof Penjualan) {

                return [
                    'nomor' => $data->nomor,
                    'tanggal' => $data->tanggal,
                    'nama' => $data->karyawan->nama,
                    'total' => $data->grand_total,
                ];
            }

            $row = '';
            if ($data->count() == 0) {
                return '<tr>
                            <td colspan="4" class="text-danger text-center" >Data Tidak Ditemukan</td>
                        </tr>';
            }
            foreach ($data as $item) {
                $row .= '<tr data-dismiss="modal" class="cursor-pointer">
                <td data-target="no_transaksi">' . $item->nomor . '</td>
                <td data-target="tanggal">' . $item->tanggal . '</td>
                <td data-target="nama">' . $item->karyawan->nama . '</td>
                <td data-target="total">' . $item->grand_total . '</td>
                </tr>';
            }

            return $row;
        }

        return view('penjualan.retur-find-trans', compact('data'));
    }

    public function store(ReturRequest  $request)
    {
        try {
            $penjualan_langsung = Penjualan::with('penjualanDetail.produk')->where('nomor', $request->nomor)->first();

            if (!$penjualan_langsung) {
                throw new Exception('Penjulan tidak ditemukan', 1);
            }
            $penjualan_langsung->retur()->save(
                new Retur([
                    'tanggal_return' => $request->tanggal_return,
                    'keterangan' => $request->keterangan,
                    'user_id' => $request->user()->id,
                    'user_input' => $request->user()->name,
                ])
            );

            $penjualanDetail = $penjualan_langsung->penjualanDetail->keyBy('produk_id');
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

            $this->repository->updateStokProduk($update_stok);

            return responseMessage();
        } catch (\Throwable $th) {
            return responseMessage('error', $th);
        }
    }

    public function show(Retur $retur_penjualan)
    {
        $retur_penjualan->load(['penjualan.karyawan', 'penjualan.penjualanDetail.produk']);
        return view('penjualan.retur-detail', ['data' => $retur_penjualan]);
    }
}
