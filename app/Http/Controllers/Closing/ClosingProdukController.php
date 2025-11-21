<?php

namespace App\Http\Controllers\Closing;

use App\Http\Controllers\Controller;
use App\Models\Closing\ClosingProduk;
use App\Models\Closing\ClosingProdukApproval;
use App\Services\ClosingProdukService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClosingProdukController extends Controller
{

    protected $service;

    public function __construct(ClosingProdukService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $list_closing = ClosingProdukApproval::orderBy('periode', 'DESC')->pluck('periode');
        return view('closing.closingproduk', compact('list_closing'));
    }

    function getData($periode)
    {

        try {
            $bulan = substr($periode, 0, 2);
            $tahun = substr($periode, 3);

            $cekExist = ClosingProduk::where('periode', $tahun . $bulan)->first();

            if ($cekExist) {
                throw new \Exception('Data sudah diclosing', 1);
            } else if ($bulan >= now()->format('m')) {
                throw new \Exception('Data belum bisa diclosing', 1);
            }
            $data =  $this->service->data($tahun, $bulan);

            return view('closing.closingproduk-detail', compact('data'));
        } catch (\Throwable $th) {
            return responseMessage('error', $th);
        }
    }

    public function proses(Request $request)
    {
        $request->validate([
            'periode' => 'required',
        ]);
        DB::beginTransaction();
        try {

            $bulan = substr($request->periode, 0, 2);
            $tahun = substr($request->periode, 3);

            if (!password_verify($request->password, $request->user()->password)) {
                throw new \Exception('Kata sandi salah', 1);
            }


            $data = $this->service->data($tahun, $bulan, true);
            ClosingProduk::insert($data);

            ClosingProdukApproval::create([
                'periode' => $tahun . $bulan,
                'tanggal_closing' => now(),
                'user_closing_id' => $request->user()->id,
                'user_closing' => $request->user()->name,
            ]);

            DB::commit();
            return responseMessage();
        } catch (\Throwable $th) {
            DB::rollBack();
            return responseMessage('error', $th);
        }
    }

    public function getClosed(Request $request, $periode)
    {
        $data = ClosingProduk::with('produk')->where('periode', $periode)->get();
        $data = $data->map(function ($item) {
            $item->nama_produk = $item->produk->nama;
            return $item;
        });
        $approval = ClosingProdukApproval::where('periode', $periode)->first();
        return view('closing.closingproduk-detail', compact('data', 'approval'));
    }

    public function approvalClosed($periode)
    {
        return view('closing.closingproduk-approve', compact('periode'));
    }

    public function postApproval(Request $request, $periode)
    {
        $request->validate([
            'approve' => 'required',
            'keterangan' => Rule::requiredIf(function () {
                return !request('approve');
            })
        ]);
        try {
            $approval = ClosingProdukApproval::where('periode', $periode)->first();
            $approval->proses = 1;
            $approval->approve = $request->approve;
            $approval->tanggal_approval = now();
            $approval->keterangan_approval = $request->keterangan;
            $approval->user_approval_id = $request->user()->id;
            $approval->user_approval = $request->user()->name;
            $approval->save();
            return responseMessage();
        } catch (\Throwable $th) {
            return responseMessage('error', $th);
        }
    }
}
