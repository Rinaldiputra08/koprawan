<?php

namespace Tests\Feature;

use App\Models\Pembelian\PenerimaanProduk;
use App\Models\User;
use App\Repositories\PenerimaanProdukRepository;
use App\Services\PenerimaanProdukService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PenerimaanProdukTest extends TestCase
{
    use WithFaker;

    private $url = 'pembelian/penerimaan-produk',
        $repository,
        $service;

    public function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
        $this->setUpFaker();
        $this->service = new PenerimaanProdukService;
        $this->repository = new PenerimaanProdukRepository;
    }

    private function getUserRandom($permission = null, $can = true)
    {
        $user = User::inRandomOrder();
        if (!$permission) return $user->first();
        if ($can) return $user->permission($permission)->first();

        $users = $user->get();
        foreach ($users as $user) {
            if (!$user->hasPermissionTo($permission)) {
                return $user;
            }
        }
    }

    private function getPenerimaan($id = null)
    {
        $penerimaan = PenerimaanProduk::inRandomOrder();
        if ($id) {
            return $penerimaan->find($id);
        }

        return $penerimaan->first();
    }

    public function test_can_show_index_page()
    {
        $user = $this->getUserRandom('read pembelian/penerimaan-produk');
        $this->actingAs($user);

        $response = $this->get($this->url)
            ->assertOk();

        if ($user->can('create pembelian/penerimaan-produk')) {
            $response->assertSee('Tambah');
        }
    }

    public function test_cannot_show_index_if_not_logged_in()
    {
        $this->get($this->url)
            ->assertRedirect();
    }

    // public function test_cannot_show_index_unauthorized()
    // {
    //     $user = $this->getUserRandom('read pembelian/penerimaan-produk', false);
    //     $this->actingAs($user);

    //     $this->get($this->url)
    //         ->assertForbidden();
    // }

    public function test_can_show_create()
    {
        $user = $this->getUserRandom('read pembelian/penerimaan-produk');
        $this->actingAs($user);

        $supplier = $this->repository->getSupplier();
        $this->get($this->url . '/create')
            ->assertOk()
            ->assertViewIs('pembelian.penerimaanproduk-action')
            ->assertViewHas('supplier', $supplier)
            ->assertSeeInOrder([
                'Tambahan Penerimaan',
                'Tagihan',
                'tagihan',
                'Tanpa Tagihan',
                'tagihan',
                'Dengan Tagihan',
                'pemesanan',
                'Ambil dari pemesanan',
                'Tanggal Penerimaan',
                'tanggal_penerimaan',
                'Supplier',
                'supplier',
                'Keterangan',
                'keterangan',
                'Daftar Produk',
                'Kode Produk',
                'id_produk',
                'kode_produk',
                'Nama Produk',
                'nama_produk',
                'Harga Satuan',
                'harga_satuan',
                'Diskon',
                'diskon',
                'nominal_diskon',
                'Qty',
                'qty',
                'Sub Total',
                'sub_total',
                'Sisa Stok',
                'stok',
                'add-item',
                'Tambah',
                'edit-item',
                'Edit',
                'table-item',
                'Referensi Penerimaan',
                'Simpan',
                'Tutup',
            ]);
    }

    public function test_can_show_detail()
    {
        $user = $this->getUserRandom('read pembelian/penerimaan-produk');
        $this->actingAs($user);

        $penerimaan = $this->getPenerimaan()->load(['supplier', 'penerimaanDetail.produk']);
        $response = $this->get($this->url . '/' . $penerimaan->id)
            ->assertOk()
            ->assertViewIs('pembelian.penerimaanproduk-detail')
            ->assertSeeInOrder([
                'Detail Penerimaan Produk',
                'Honda Bintaro',
                'Supplier',
                $penerimaan->supplier->nama,
                'Nomor Telp.',
                $penerimaan->supplier->nomor_telepon,
                'Alamat',
                $penerimaan->supplier->alamat,
                "Nomor : " . $penerimaan->nomor . " / " . $penerimaan->tanggal_penerimaan_formatted,
                'Dibuat oleh ' . $penerimaan->user_input
            ])
            ->assertSee('Tutup')
            ->assertDontSee('Simpan');

        if ($penerimaan->tanggal_batal) {
            $response->assertSeeText('Dibatalkan oleh ' . $penerimaan->user_batal)
                ->assertSee($penerimaan->keterangan_batal);
        }

        foreach ($penerimaan->penerimaanDetail as $penerimaanDetail) {
            $response->assertSeeText($penerimaanDetail->produk->id)
                ->assertSeeText($penerimaanDetail->produk->nama)
                ->assertSeeText($penerimaanDetail->qty_formatted)
                ->assertSeeText($penerimaanDetail->produk->harga_beli_formatted)
                ->assertSeeText($penerimaanDetail->diskon_formatted)
                ->assertSeeText($penerimaanDetail->total_formatted);
        }
    }
}
