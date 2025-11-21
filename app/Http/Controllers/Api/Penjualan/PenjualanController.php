<?php

namespace App\Http\Controllers\Api\Penjualan;

use App\Http\Controllers\Controller;
use App\Http\Requests\PenjualanRequest;
use App\Models\Penjualan\Cart;
use App\Models\Penjualan\Penjualan;
use App\Models\Penjualan\PenjualanDetail;
use App\Repositories\PenjualanRepository;
use App\Services\PenjualanService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{

    private $repository, $service;

    public function __construct(PenjualanRepository $repository, PenjualanService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $limit = 20;
        $offset = 0;
        if($request->has('page')){
            if (is_numeric($request->page)) {
                $offset = ($request->page - 1) * $limit;
            } 
        }
        
        $jenis = $request->jenis == 'langsung' ? 'Langsung' : 'Online';
        $penjualan = Penjualan::with('penjualanDetail:id,penjualan_id,produk_id,produk_type','penjualanDetail.produk:id,nama,judul')
        ->select('id','jenis','nomor','grand_total','tanggal')
        ->where('jenis', $jenis)
        ->offset($offset)->limit($limit)->orderByDesc('id')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $penjualan,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PenjualanRequest $request)
    {
        $carts = collect($request->carts);

        DB::beginTransaction();
        try {
            $data_carts = Cart::whereIn('id', $carts->pluck('id'))->where('terjual', 0)->get();
            // validasi if request given is in cart list
            if ($data_carts->count() != $carts->count()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data cart tidak sesuai'
                ], 403);
            }

            $data_carts = $data_carts->load('produk.diskon');

            $total_diskon = 0;
            $total_harga = 0;
            $update_stok = [];

            foreach ($data_carts as $cart_item) {
                $produk = $cart_item->produk;
                // validasi stock product
                if ($cart_item->qty > $produk->stock_free) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Ada stock yang tidak mencukupi',
                        'data' => $produk
                    ], 403);
                }
                $total_harga_produk = $cart_item->qty * $produk->harga_jual;
                $diskon_produk =  $cart_item->qty * ($cart_item->diskon->nominal ?? 0);
                $total_diskon += $diskon_produk;
                $total_harga += $total_harga_produk;

                // prepare data for penjualan detail
                $item_produk[] = new PenjualanDetail([
                    'produk_id' => $produk->id,
                    'produk_type' => $cart_item->produk_type,   
                    'harga' => $produk->harga_jual,
                    'qty' => $cart_item->qty,
                    'hpp' => $produk->hpp,
                    'total_harga' => $total_harga_produk,
                    'diskon_id' => $produk->diskon->id ?? null,
                    'nominal_diskon' => $produk->diskon->nominal ?? 0,
                    'grand_total' => $total_harga_produk - $diskon_produk,
                ]);

                // prepare data for update stock (calculate current stock with the new product sale)
                $update_stok[] = [
                    'id' => $produk->id,
                    'stock_free' => $produk->stock_free  - $cart_item->qty,
                ];
            }

            $request->karyawan_id = $request->user()->id;
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
            $penjualan = $this->repository->store($request, 'online');
            // store penjualan detail
            $penjualan->penjualanDetail()->saveMany($item_produk);
            // store voucher if present
            if(isset($trans_voucher)){
                $penjualan->voucher()->saveMany($trans_voucher);
            }
            // update status cart
            $this->repository->updateCart($data_carts);
            
            // update stock
            $this->repository->updateStokProduk($update_stok);
            DB::commit();
            return responseMessage('success');
        } catch (\App\Helpers\ResponseException $th) {
            DB::rollBack();
            return responseError($th, $th->getData());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penjualan\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function show($penjualan)
    {
        $penjualan = $this->repository->show($penjualan);
        if(!$penjualan){
            return responseNotFound();
        }   
        return response()->json([
            'status' => 'success',
            'data' => $penjualan
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penjualan\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penjualan $penjualan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penjualan\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penjualan $penjualan)
    {
        //
    }
}
