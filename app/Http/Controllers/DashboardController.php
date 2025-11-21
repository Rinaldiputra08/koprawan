<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonitoringService;
use App\Services\DashboardService;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    protected $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $data = null;
        return view('dashboard', compact('user', 'data'));
    }

    public function detail($jenis)
    {
        $data = $this->service->detail($jenis);
        return response()->json($data);
    }

    public function komparasi()
    {
        $data = $this->service->komparasi();
        return response()->json($data);
    }

    public function outstanding()
    {
        $data = $this->service->outstanding();
        return response()->json($data);
    }

    public function pencapaian()
    {
        $data = $this->service->pencapaian();
        return response()->json($data);
    }

    public function leads($jenis)
    {
        $data = $this->service->leads($jenis);
        return response()->json($data);
    }
}
