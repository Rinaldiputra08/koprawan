<?php

namespace App\Http\Controllers\Konfigurasi;

use App\DataTables\SetupApplicationDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\SetupApplicationRequest;
use App\Models\SetupApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SetupApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SetupApplicationDataTable $datatable)
    {
        return $datatable->render('konfigurasi.setupapplication');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(SetupApplication $setup_aplikasi)
    {
        return view('konfigurasi.setupapplication-action', ['data' => $setup_aplikasi]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SetupApplicationRequest $request)
    {
        try {
            $setup = SetupApplication::create([
                'name' => $request->nama,
                'value' => $request->jenis == 'json' ? preg_replace('/[\s]/', '', $request->nilai) : $request->nilai
            ]);

            Cache::forget('config');

            return responseMessage();
        } catch (\Throwable $th) {
            return responseMessage('error', $th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SetupApplication $setup_aplikasi)
    {
        return view('konfigurasi.setupapplication-action', ['data' => $setup_aplikasi]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SetupApplicationRequest $request, SetupApplication $setup_aplikasi)
    {
        try {
            $setup_aplikasi->value = $request->jenis == 'json' ? preg_replace('/[\s]/', '', $request->nilai) : $request->nilai;
            $setup_aplikasi->save();
            Cache::forget('config');
            return responseMessage();
        } catch (\Throwable $th) {
            return responseMessage('error', $th);
        }
    }
}
