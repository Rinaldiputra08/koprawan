<?php

namespace Tests\Feature;

use App\Models\MasterData\Karyawan;
use App\Models\MasterData\Produk;
use App\Models\Penjualan\Penjualan;
use App\Models\Penjualan\PenjualanDetail;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PenjualanLangsungTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use WithFaker;

    private $url = 'penjualan/penjualan-langsung';

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    private function getUserRandom($permission = null, $can = true)
    {
        $user = User::inRandomOrder();

        if (!$permission) return $user->first();
        if ($can) return $user->permission($permission)->first();

        $user = $user->get();

        foreach ($user as $user) {
            if (!$user->hasPermissionTo($permission)) {
                return $user;
            }
        }
    }

    public function testCanShowPagePenjualanLangsung()
    {
        $user = $this->getUserRandom('read penjualan/penjualan-langsung');
        $this->actingAs($user);

        $response = $this->get($this->url)
            ->assertOk();
        if ($user->can('create penjualan/penjualan-langsung')) {
            $response->assertSee('Tambah');
        }
    }

    public function testShowPagePenjualanLangsungNotLogin()
    {
        $this->get($this->url)
            ->assertRedirect();
    }

    public function testShowPagePenjualanLangsungUnauthorized()
    {
        $user = $this->getUserRandom('read penjualan/penjualan-langsung', false);
        $this->actingAs($user);

        $this->get($this->url)
            ->assertForbidden();
    }

    public function testCanCreatePenjualanLangsung()
    {
        $user = $this->getUserRandom('read penjualan/penjualan-langsung');
        $this->actingAs($user);

        $this->get($this->url . '/create')
            ->assertOk()
            ->assertViewIs('penjualan.penjualanlangsung-action');
    }

    public function testCannotCreatePenjualanLangsungUnauthorized()
    {
        $user = $this->getUserRandom('read penjualan/penjualan-langsung', false);
        $this->actingAs($user);

        $this->get($this->url . '/create')
            ->assertForbidden();
    }

    public function testCannotCreatePenjualanLangsungNotLogin()
    {
        $this->get($this->url . '/create')
            ->assertRedirect();
    }

    public function testStorePenjualannLangsung()
    {
        $user = $this->getUserRandom('read penjualan/penjualan-langsung');
        $this->actingAs($user);

        $karyawan = Karyawan::inRandomOrder()->first();
        $produk = Produk::with('diskon')->where('stock_free', '>', '1')->Where('stock_fisik', '>', '1')->inRandomOrder()->limit(random_int(1, 9))->get();
        $qty = [];
        $expected_stock_fisik = [];
        $expected_stock_free = [];
        $expected_detail = [];

        foreach ($produk as $p) {
            $qty[$p->id] = random_int(1, 9);
            $expected_stock_fisik[$p->id] = $p->stock_fisik - $qty[$p->id];
            $expected_stock_free[$p->id] = $p->stock_free - $qty[$p->id];

            $expected_detail[$p->id] = [
                'qty' => $qty[$p->id],
                'produk_id' => $p->id,
                'diskon_id' => $p->diskon->id ?? null,
                'harga'     => $p->harga_jual,
                'nominal_diskon' => $p->diskon->nominal ?? 0,
                'grand_total' => $qty[$p->id] * ($p->harga_jual -   ($p->diskon->nominal ?? 0)),
                'total_harga' => $qty[$p->id] * $p->harga_jual,
            ];
        }

        $this->post($this->url, [
            'karyawan' => $karyawan->uuid,
            'qty' => $qty
        ])
            ->assertOk()
            ->assertJson(['status' => 'success', 'message' => 'Data berhasil disimpan']);

        $actual_produk = Produk::whereIn('id', $produk->pluck('id'))->get();
        $actual_master = Penjualan::orderBy('id', 'DESC')->first();
        $actual_detail = PenjualanDetail::where('penjualan_id', $actual_master->id)->whereIn('produk_id', $produk->pluck('id'))->get();

        foreach ($actual_produk as $ap) {
            $this->assertEquals($expected_stock_fisik[$ap->id], $ap->stock_fisik, 'stok fisik');
            $this->assertEquals($expected_stock_free[$ap->id], $ap->stock_free, 'stok free');
        }

        foreach ($actual_detail as $ad) {
            $this->assertEquals($expected_detail[$ad->produk_id]['qty'], $ad->qty);
            $this->assertEquals($expected_detail[$ad->produk_id]['produk_id'], $ad->produk_id);
            $this->assertEquals($expected_detail[$ad->produk_id]['harga'], $ad->harga);
            $this->assertEquals($expected_detail[$ad->produk_id]['total_harga'], $ad->total_harga);
            $this->assertEquals($expected_detail[$ad->produk_id]['diskon_id'], $ad->diskon_id);
            $this->assertEquals($expected_detail[$ad->produk_id]['nominal_diskon'], $ad->nominal_diskon);
            $this->assertEquals($expected_detail[$ad->produk_id]['grand_total'], $ad->grand_total);
        }
        $this->assertEquals($user->id, $actual_master->user_id);
        $this->assertEquals($user->name, $actual_master->user_input);
    }

    public function testValidationStoreWithoutChooseProduk()
    {
        $user = $this->getUserRandom('read penjualan/penjualan-langsung');
        $this->actingAs($user);

        $this->postJson($this->url);

        $karyawan = Karyawan::inRandomOrder()->first();

        $this->post($this->url, [
            'karyawan' => $karyawan->uuid
        ])
            ->assertOk()
            ->assertJson(['status' => 'error', 'message' => 'Belum memilih produk']);
    }

    public function testQtyCannotBiggerThanStock()
    {
        $user = $this->getUserRandom('read penjualan/penjualan-langsung');
        $this->actingAs($user);

        $produk = Produk::where('stock_free', '<', 50)->orWhere('stock_fisik', '<', 50)->limit(random_int(1, 9))->get();
        $karyawan = Karyawan::inRandomOrder()->first();
        $qty = [];
        $expected_stock_fisik = [];
        $expected_stock_free = [];
        $expected_detail = [];
        foreach ($produk as $p) {
            $qty[$p->id] = 50;
            $qty[$p->id] = random_int(1, 9);
            $expected_stock_fisik[$p->id] = $p->stock_fisik - $qty[$p->id];
            $expected_stock_free[$p->id] = $p->stock_free - $qty[$p->id];

            $expected_detail[$p->id] = [
                'qty' => $qty[$p->id],
                'produk_id' => $p->id,
                'diskon_id' => $p->diskon->id ?? null,
                'harga'     => $p->harga_jual,
                'nominal_diskon' => $p->diskon->nominal ?? 0,
                'grand_total' => $qty[$p->id] * ($p->harga_jual -   ($p->diskon->nominal ?? 0)),
                'total_harga' => $qty[$p->id] * $p->harga_jual,
            ];
        }

        $this->post($this->url, [
            'karyawan' => $karyawan->uuid,
            'qty' => $qty
        ])
            ->assertOk()
            ->assertJson(['status' => 'error', 'message' => 'Gagal, qty melebihi stock produk ' . $produk->first()->kode . ': ' . $produk->first()->stock_free]);
    }

    public function testCannotStoreProdukDosntExist()
    {
        $user = $this->getUserRandom('read penjualan/penjualan-langsung');
        $this->actingAs($user);

        $karyawan = Karyawan::inRandomOrder()->first();

        $this->post($this->url, [

            'karyawan' => $karyawan->uuid,
            'qty' => [
                'rtr' => 5
            ],


        ])
            ->assertOk()
            ->assertJson(['status' => 'error', 'message' => 'Data produk tidak tersedia']);
    }

    public function testCanShowDetailPenjualanLangsung()
    {
        $user = $this->getUserRandom('read penjualan/penjualan-langsung');
        $this->actingAs($user);


        $pldetail = Penjualan::with(['penjualanDetail.produk'])->inRandomOrder()->first();
        $response = $this->get($this->url . '/' . $pldetail->id)
            ->assertOk()
            ->assertViewIs('penjualan.penjualanlangsung-detail');


        foreach ($pldetail->penjualanDetail as $plDetail) {
            $response->assertSee($plDetail->produk->nama)
                ->assertSee($plDetail->kode)
                ->assertSee($plDetail->qty);
        }
    }
}
