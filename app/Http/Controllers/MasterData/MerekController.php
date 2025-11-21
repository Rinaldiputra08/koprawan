<?php

namespace App\Http\Controllers\MasterData;

use App\DataTables\MerekDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\MerekRequest;
use App\Models\MasterData\Merek;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MerekController extends Controller
{
    private $uploadService;
    public function __construct(UploadService $uploadService) {
        $this->uploadService = $uploadService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MerekDataTable $datatable)
    {
        return $datatable->render('master-data.merek');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Merek $merek)
    {
        return view('master-data.merek-action',['data' => $merek]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerekRequest $request)
    {
        DB::beginTransaction();
        try {
            $merek = Merek::create(['nama' => $request->nama, 'user_id' => $request->user()->id]);
            if($request->upload_foto){
                $foto = $this->uploadService->uploadFromBase64($request->upload_foto,'produk',time().rand());
                $merek->foto()->create(['nama_file' => $foto]);
            }
            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterData\Merek  $merek
     * @return \Illuminate\Http\Response
     */
    public function show(Merek $merek)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterData\Merek  $merek
     * @return \Illuminate\Http\Response
     */
    public function edit(Merek $merek)
    {
        return view('master-data.merek-action',['data' => $merek]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterData\Merek  $merek
     * @return \Illuminate\Http\Response
     */
    public function update(MerekRequest $request, Merek $merek)
    {
        DB::beginTransaction();
        try {
            $merek->nama = $request->nama;
            $merek->aktif = $request->aktif;
            $merek->save();
            if($request->upload_foto){
                if($merek->foto){
                    $merek->foto()->delete();
                    $this->uploadService->deleteFoto($merek->foto->nama_file,'public/images/produk/');
                }
                $foto = $this->uploadService->uploadFromBase64($request->upload_foto, 'produk', time().rand());
                $merek->foto()->create(['nama_file' => $foto]);
            }
            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterData\Merek  $merek
     * @return \Illuminate\Http\Response
     */
    public function destroy(Merek $merek)
    {
        //
    }
}
