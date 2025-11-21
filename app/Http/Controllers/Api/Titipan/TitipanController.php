<?php

namespace App\Http\Controllers\Api\Titipan;

use App\Http\Controllers\Controller;
use App\Http\Requests\TitipanRequest;
use App\Models\MasterData\Foto;
use App\Models\Titipan\Titipan;
use Illuminate\Validation\Rule;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToArray;

class TitipanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $uploadService;
    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function index(Request $request)
    {
        $offset = 0;
        $limit = 20;
        if ($request->has('page')) {
            if (is_numeric($request->page)) {
                $offset = ($request->page - 1) * 20;
            }
            $limit = 20;
        }


        $titipans = Titipan::with('fotoThumbnail:id,nama_file,referensi_id,referensi_type')
            ->select('id', 'uuid', 'judul', 'slug', 'nama', 'harga_jual', 'tanggal_awal_penjualan', 'tanggal_akhir_penjualan', 'approval', 'karyawan_id', 'deskripsi')
            ->offset($offset)->limit($limit)->get();

        $titipans = collect($titipans->toArray())->map(function ($titipan) {
            if ($titipan['foto_thumbnail']) {

                $foto_thumbnail =   asset('storage/images/titipan-produk/small_' . $titipan['foto_thumbnail']['nama_file']);
            } else {
                $foto_thumbnail = asset('assets/images/image-placeholder.jpg');
            }

            $titipan['jenis'] = 'Produk titipan';
            $titipan['foto_thumbnail'] = $foto_thumbnail;
            return $titipan;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $titipans,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TitipanRequest $request)
    {
        $titipan = Titipan::create([
            'uuid' => Str::uuid(),
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul),
            'nama' => $request->nama,
            'harga_jual' => (int)str_replace('.', '', $request->harga_jual),
            'deskripsi' => $request->deskripsi,
            'karyawan_id' => $request->user()->id,
            'tanggal_awal_penjualan' => $request->tanggal_awal_penjualan,
            'tanggal_akhir_penjualan' => $request->tanggal_akhir_penjualan,
            'stock_fisik' => $request->stock,
            'stock_free' => $request->stock,
        ]);

        if ($request->hasFile('foto')) {
            $foto = [];
            for ($i = 0; $i < count($request->foto); $i++) {
                $fileName = $this->uploadService->uploadFoto($request->file('foto')[$i], 'produk-titipan');
                $foto[] = new Foto([
                    'nama_file' => $fileName,
                    'thumbnail' => $request->foto_thumbnail == $i
                ]);
            }
            $titipan->foto()->saveMany($foto);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Titipan berhasil di simpan',
            'data' => $titipan,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Titipan $titipan)
    {
        $titipan->load('foto');

        if (!$titipan) {
            return responseNotFound();
        }

        $titipan = $titipan->toArray();

        if ($titipan['foto']) {
            $titipan['foto'] = collect($titipan['foto'])->map(function ($foto) {
                $data['url'] = asset('storage/images/produk-titipan/medium_' . $foto['nama_file']);
                return $data;
            });
        } else {
            $titipan['foto'] = [['url' => $titipan['foto_thumbnail'] = asset('assets/images/image-placeholder.jpg')]];
        }
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $titipan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TitipanRequest $request, Titipan $titipan)
    {
        $titipan->judul = $request->judul;
        $titipan->nama_produk = $request->nama_produk;
        $titipan->harga_jual = $request->harga_jual;
        $titipan->deskripsi_produk = $request->deskripsi_produk;
        $titipan->tanggal_awal_penjualan = $request->tanggal_awal_penjualan;
        $titipan->tanggal_akhir_penjualan = $request->tanggal_akhir_penjualan;
        $titipan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil di update',
            'data' => $titipan,
        ]);
    }

    public function approve(TitipanRequest $request, Titipan $titipan)
    {
        $sharing_profit = getConfig('sharing_profit');
        $titipan->sharing_profit = $sharing_profit;
        $titipan->approval = $request->approval;
        $titipan->keterangan_approval = $request->keterangan_approval;
        $titipan->user_approve_id = $request->user()->id;
        $titipan->user_approve_nama = $request->user()->nama;
        $titipan->tanggal_approval = now();

        $titipan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Titipan berhasil di approve',
            'data' => $titipan,
        ]);
    }

    public function batal(TitipanRequest $request, Titipan $titipan)
    {
        if ($titipan->approval == 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data sudah di approve tidak bisa di batalkan',
                'data' => []
            ], 403);
        }
        $titipan->batal = $request->batal;
        $titipan->keterangan_batal = $request->keterangan_batal;

        $titipan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil di batalkan',
            'data' => $titipan
        ]);
    }

    public function getTitipanKaryawan(Request $request, $jenis)
    {
        $offset = 0;
        $limit = 20;
        if ($request->has('page')) {
            if (is_numeric($request->page)) {
                $offset = ($request->page - 1) * 20;
            }
            if ($request->has('limit')) {
                if (is_numeric($request->limit)) {
                    $limit = $request->limit;
                }
            }
        }


        $titipans = Titipan::with('fotoThumbnail:id,nama_file,referensi_id,referensi_type')
            ->where('karyawan_id', $request->user()->id)
            ->where(function ($query) use ($jenis) {
                if ($jenis == 'disetujui') {
                    $query->where('approval', 1);
                } elseif ($jenis == 'ditolak') {
                    $query->where('approval', 0);
                } elseif ($jenis == 'menunggu') {
                    $query->whereNull('approval');
                }
            })
            ->offset($offset)
            ->limit($limit)
            ->get();

        $titipans = collect($titipans->toArray())->map(function ($titipan) {
            if ($titipan['foto_thumbnail']) {
                $foto_thumbnail =   asset('storage/images/produk-titipan/small_' . $titipan['foto_thumbnail']['nama_file']);
            } else {
                $foto_thumbnail = asset('assets/images/image-placeholder.jpg');
            }

            return [
                'uuid' => $titipan['uuid'],
                'judul' => $titipan['judul'],
                'slug' => $titipan['slug'],
                'nama_produk' =>  $titipan['nama_produk'],
                'deskripsi_produk' =>  $titipan['deskripsi_produk'],
                'harga_jual' =>  $titipan['harga_jual'],
                'tanggal_awal_penjualan' => $titipan['tanggal_awal_penjualan'],
                'tanggal_akhir_penjualan' =>  $titipan['tanggal_akhir_penjualan'],
                'status_approve' => $titipan['approval'],
                'karyawan' => $titipan['karyawan_id'],
                'rating' => $titipan['rating'],
                'stock_fisik' => $titipan['stock_fisik'],
                'stock_free' => $titipan['stock_free'],
                'foto_thumbnail' => $foto_thumbnail,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $titipans,
        ]);
    }
}
