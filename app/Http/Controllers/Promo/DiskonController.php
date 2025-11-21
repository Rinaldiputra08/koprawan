<?php

namespace App\Http\Controllers\Promo;

use App\DataTables\DiskonDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\DiskonRequest;
use App\Models\MasterData\Produk;
use App\Models\Promo\Diskon;
use Illuminate\Http\Request;

class DiskonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DiskonDataTable $datatable)
    {
        return $datatable->render('promo.diskon');
    }

    /**
     * Show the form for creating a new resource.
     * @param \App\Models\Promo\Diskon $diskon
     * @return \Illuminate\Http\Response
     */
    public function create(Diskon $diskon)
    {
        return view('promo.diskon-action',['data' => $diskon]);
    }

    private function _listProduk()
    {
        return Produk::with('fotoThumbnail:referensi_id,nama_file')->active()->limit(20)->orderBy('stock_free','desc');
    }

    public function listProduk(Request $request)
    {
        if($request->has('filter')){
            $list_produk = $this->_listProduk()->where('kode','like',"%{$request->filter}%")->orWhere('nama', 'like',"%{$request->filter}%")->get();
            $row = '';
            foreach ($list_produk as $produk) {
                $row.= '<tr class="cursor-pointer" data-dismiss="modal">
                        <td class="d-none">'.$produk->id.'</td>
                        <td>'.($produk->fotoThumbnail ? '<img class="rounded" style="width: 60px;height:60px" src="'.asset('storage/images/produk/small_'.$produk->fotoThumbnail->nama_file).'" />' : '').'</td>
                        <td>'.$produk->kode.'</td>
                        <td>'.$produk->nama.'</td>
                    </tr>';
            }
            return $row;
        }
        $list_produk = $this->_listProduk()->get();
        return view('promo.diskon-list-produk',['list_produk' => $list_produk]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiskonRequest $request)
    {
        $produk = Produk::find($request->produk_id);
        if(!$produk){
            return responseMessage('error', 'Produk tidak ada');
        }

        $cek_belaku = $produk->diskon()->berlaku()->first();
        if($cek_belaku) return responseMessage('error', 'Gagal, Masih ada periode diskon yang berlaku');

        $produk->diskon()->create([
            'tanggal_awal' => $request->tanggal_awal. " ".$request->jam_awal.':'.$request->menit_awal,
            'tanggal_akhir' => $request->tanggal_akhir. " ". $request->jam_akhir.':'.$request->menit_akhir,
            'nominal' => $request->nominal,
            'user_id' => $request->user()->id
        ]);

        return responseMessage();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Promo\Diskon  $diskon
     * @return \Illuminate\Http\Response
     */
    public function show(Diskon $diskon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Promo\Diskon  $diskon
     * @return \Illuminate\Http\Response
     */
    public function edit(Diskon $diskon)
    {
        return view('promo.diskon-action',['data' => $diskon]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Promo\Diskon  $diskon
     * @return \Illuminate\Http\Response
     */
    public function update(DiskonRequest $request, Diskon $diskon)
    {
        $produk = Produk::find($request->produk_id);
        if(!$produk){
            return responseMessage('error', 'Produk tidak ada');
        }
        $diskon->nominal = $request->nominal;
        $diskon->tanggal_awal = $request->tanggal_awal. " ".$request->jam_awal.':'.$request->menit_awal;
        $diskon->tanggal_akhir = $request->tanggal_akhir. " ". $request->jam_akhir.':'.$request->menit_akhir;
        $diskon->produk_id = $request->produk_id;
        $diskon->save();

        return responseMessage();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Promo\Diskon  $diskon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diskon $diskon)
    {
        //
    }
}
