<?php

namespace App\Http\Controllers\Titipan;

use App\DataTables\TitipanDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\TitipanRequest;
use App\Models\Titipan\Titipan;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class TitipanController extends Controller
{
    public function index(TitipanDataTable $datatable)
    {
        return $datatable->render('titipan.produkTitipan');
    }

    public function show($id)
    {
        $titipan = Titipan::with('foto', 'karyawan')->where('id', $id)->first();

        return view('titipan.produkTitipan-detail', ['data' => $titipan]);
    }

    public function approve(Titipan $titipan)
    {
        return view('titipan.produkTitipan-approve', compact('titipan'));
    }

    public function postApprove(Request $request, Titipan $titipan)
    {
        $request->validate([
            'approval' => 'required',
            'keterangan_approval' => Rule::requiredIf(function () {
                return !request('approval');
            })
        ]);
        try {
            $sharing_profit = getConfig('sharing_profit');
            $titipan->sharing_profit = $sharing_profit;
            $titipan->approval = $request->approval;
            $titipan->tanggal_approval = now();
            $titipan->keterangan_approval = $request->keterangan_approval;
            $titipan->user_approve_id = $request->user()->id;
            $titipan->user_approve_nama = $request->user()->name;
            $titipan->save();
            return responseMessage();
        } catch (\Throwable $th) {
            return responseMessage('error', $th);
        }
    }
}
