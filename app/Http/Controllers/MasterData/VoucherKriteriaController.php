<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\VoucherKriteria;
use Illuminate\Http\Request;
use App\DataTables\VoucherKriteriaDataTable;
use App\Http\Requests\VoucherKriteriaRequest;
use Illuminate\Support\Facades\DB;

class VoucherKriteriaController extends Controller
{

    public function index(VoucherKriteriaDataTable $datatable)
    {
        return $datatable->render('master-data.voucher-kriteria');
    }
    public function create(VoucherKriteria $voucher)
    {
        return view('master-data.voucher-kriteria-action', ['data' => $voucher]);
    }
    public function store(VoucherKriteriaRequest $request)
    {
        VoucherKriteria::create([
            'nama' => $request->nama,
            'nominal' => $request->nominal,
            'user_id' => $request->user()->id
        ]);
        return responseMessage();
    }

    public function edit($id)
    {
        $voucher = VoucherKriteria::find($id);
        return view('master-data.voucher-kriteria-action', ['data' => $voucher]);
    }

    public function update(VoucherKriteriaRequest $request, VoucherKriteria $voucher_kriterium)
    {
        // $voucher = VoucherKriteria::find($id);
        $voucher_kriterium->nama = $request->nama;
        $voucher_kriterium->nominal = $request->nominal;
        $voucher_kriterium->save();

        return responseMessage();
    }
}
