<?php

namespace Tests\Feature;

use App\Models\Gudang\BeritaAcaraGudang;
use App\Models\Gudang\BeritaAcaraGudangDetail;
use App\Models\MasterData\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BeritaAcaraGudangTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use WithFaker;

    private $url = 'gudang/berita-acara-gudang';

    public function setUp():void
    {
        parent::setUp();
        // DB::beginTransaction();
        $this->setUpFaker();
    }

    private function getUserRandom($permission = null, $can = true)
    {
        $user = User::inRandomOrder();

        if(!$permission) return $user->first();
        if($can) return $user->permission($permission)->first();

        $user = $user->get();

        foreach($user as $user){
            if(!$user->hasPermissionTo($permission)){
                return $user;
            }
        }
    }

    public function testCanShowPageBeritaAcaraGudang()
    {
        $user = $this->getUserRandom('read gudang/berita-acara-gudang');
        $this->actingAs($user);
        
        $response = $this->get($this->url)
            ->assertOk();
        
        if($user->can('create gudang/berita-acara-gudang')){
            $response->assertSee('Tambah');
        }
    }

    public function testShowPageBeritaAcaraGudangNotLogin()
    {
        $this->get($this->url)
        ->assertRedirect();
    }

    public function testShowPageBeritaAcaraGudangUnauthorized()
    {
        $user = $this->getUserRandom('read gudang/berita-acara-gudang', false);
        $this->actingAs($user);

        $this->get($this->url)
        ->assertForbidden();
    }

    public function testCanCreateBeritaAcaraGudang()
    {
        $user = $this->getUserRandom('read gudang/berita-acara-gudang');
        $this->actingAs($user);

        $this->get($this->url . '/create')  
            ->assertOk()
            ->assertViewIs('gudang.berita_acara_gudang_action')
            ->assertSeeInOrder([
                'Tambahan berita acara',
                'Tanggal Berita Acara',
                'tanggal_berita_acara',
                'Jenis Berita Acara',
                'jenis',
                'Keterangan',
                'keterangan',
                'Kode Produk',
                'id_produk',
                'Nama Produk',
                'nama',
                'Qty',
                'qty',
                'Keterangan',
                'keterangan_produk',
                'Tambah',
                'Edit',
                'Simpan',
                'Tutup'
            ]);
    }

    public function testCannotCreateBeritaAcaraGudangUnauthorized()
    {
        $user = $this->getUserRandom('read gudang/berita-acara-gudang', false);
        $this->actingAs($user);

        $this->get($this->url . '/create')
        ->assertForbidden();

    }
    
    public function testCannotCreateBeritaAcaraGudangNotLogin()
    {
        $this->get($this->url . '/create')
        ->assertRedirect();
    }

    public function testStoreBeritaAcaraGudangMasuk()
    {
       $user = $this->getUserRandom('read gudang/berita-acara-gudang');
       $this->actingAs($user);

       $tanggal_berita_acara = now()->format('d-m-Y');
       $keterangan = $this->faker->sentence(3);
       $produk = Produk::inRandomOrder()->limit(random_int(1,9))->get();
       $qty = [];
       $keterangan_produk= [];
       $expected_stock_fisik = [];
       $expected_stock_free = [];
       $expected_detail = [];
       foreach ($produk as $p) {
            $qty[$p->id] = random_int(1,9);
            $keterangan_produk[$p->id] = $this->faker->sentence(3);

            $expected_stock_fisik[$p->id] = $p->stock_fisik + $qty[$p->id]; 
            $expected_stock_free[$p->id] = $p->stock_free + $qty[$p->id]; 
            
            $expected_detail[$p->id] = [
                'qty' => $qty[$p->id],
                'keterangan' => $keterangan_produk[$p->id]
        ];

       }

       $this->post($this->url, [
        'tanggal_berita_acara'=>$tanggal_berita_acara,
        'jenis'=>'Masuk',
        'keterangan'=>$keterangan,
        'qty' => $qty,
        'keterangan_produk' => $keterangan_produk
       ])
       ->assertOk()
       ->assertJson(['status'=>'success','message' => 'Data berhasil disimpan']);
       
       $actual_produk = Produk::whereIn('id', $produk->pluck('id'))->get();
       $actuan_master = BeritaAcaraGudang::orderBy('id', 'DESC')->first();
       $actuan_detail = BeritaAcaraGudangDetail::where('berita_acara_gudang_id', $actuan_master->id)->whereIn('produk_id', $produk->pluck('id'))->get();

       foreach ($actual_produk as $ap) {
            $this->assertEquals($expected_stock_fisik[$ap->id], $ap->stock_fisik);
            $this->assertEquals($expected_stock_free[$ap->id], $ap->stock_free);
       }

       foreach ($actuan_detail as $ad){
            $this->assertEquals($expected_detail[$ad->produk_id]['qty'], $ad->qty);
            $this->assertEquals($expected_detail[$ad->produk_id]['keterangan'], $ad->keterangan);
       }
       $this->assertEquals(convertDate($tanggal_berita_acara), $actuan_master->tanggal_berita_acara);
       $this->assertEquals($keterangan, $actuan_master->keterangan);
       $this->assertEquals('Masuk', $actuan_master->jenis);
       $this->assertEquals($user->id, $actuan_master->user_id);
       $this->assertEquals($user->name, $actuan_master->user_input);
       
    }

    public function testStoreBeritaAcaraGudangKeluar()
    {
       $user = $this->getUserRandom('read gudang/berita-acara-gudang');
       $this->actingAs($user);

       $tanggal_berita_acara = now()->format('d-m-Y');
       $keterangan = $this->faker->sentence(3);
       $produk = Produk::where('stock_free', '>', '1')->Where('stock_fisik', '>', '1')->inRandomOrder()->limit(random_int(1,9))->get();
       $qty = [];
       $keterangan_produk= [];
       $expected_stock_fisik = [];
       $expected_stock_free = [];
       $expected_detail = [];
       foreach ($produk as $p) {
        $qty[$p->id] = 1;
        $keterangan_produk[$p->id] = $this->faker->sentence(3);

        $expected_stock_fisik[$p->id] = $p->stock_fisik - $qty[$p->id]; 
        $expected_stock_free[$p->id] = $p->stock_free - $qty[$p->id]; 
        
        $expected_detail[$p->id] = [
            'qty' => $qty[$p->id],
            'keterangan' => $keterangan_produk[$p->id]
        ];

       }

       $this->post($this->url, [
        'tanggal_berita_acara'=>$tanggal_berita_acara,
        'jenis'=>'Keluar',
        'keterangan'=>$keterangan,
        'qty' => $qty,
        'keterangan_produk' => $keterangan_produk
       ])
       ->assertOk()
       ->assertJson(['status'=>'success','message' => 'Data berhasil disimpan']);
       
       $actual_produk = Produk::whereIn('id', $produk->pluck('id'))->get();
       $actuan_master = BeritaAcaraGudang::orderBy('id', 'DESC')->first();
       $actuan_detail = BeritaAcaraGudangDetail::where('berita_acara_gudang_id', $actuan_master->id)->whereIn('produk_id', $produk->pluck('id'))->get();


       foreach ($actual_produk as $ap) {
            $this->assertEquals($expected_stock_fisik[$ap->id], $ap->stock_fisik, 'stok fisik');
            $this->assertEquals($expected_stock_free[$ap->id], $ap->stock_free, 'stok free');
       }

       foreach ($actuan_detail as $ad){
            $this->assertEquals($expected_detail[$ad->produk_id]['qty'], $ad->qty);
            $this->assertEquals($expected_detail[$ad->produk_id]['keterangan'], $ad->keterangan);
       }
       $this->assertEquals(convertDate($tanggal_berita_acara), $actuan_master->tanggal_berita_acara);
       $this->assertEquals($keterangan, $actuan_master->keterangan);
       $this->assertEquals('Keluar', $actuan_master->jenis);
       $this->assertEquals($user->id, $actuan_master->user_id);
       $this->assertEquals($user->name, $actuan_master->user_input);
       
    }

    public function testValidationBeritaAcaraGudangRequired()
    {
        $user = $this->getUserRandom('read gudang/berita-acara-gudang');
        $this->actingAs($user);

        $this->postJson($this->url)
        ->assertJsonStructure([
            'message', 'errors'
        ])
    ->assertJsonValidationErrors([
        'jenis',
        'tanggal_berita_acara',
        'keterangan',
    ])
    ->assertStatus(422);
    }

    public function testCanShowDetailBeritaAcaraGudang()
    {
        $user = $this->getUserRandom('read gudang/berita-acara-gudang');
        $this->actingAs($user);

        
        $badetail = BeritaAcaraGudang::with(['beritaAcaraGudangDetail.produk'])->inRandomOrder()->first();
        $response = $this->get($this->url . '/' . $badetail->id)
        ->assertOk()
        ->assertViewIs('gudang.beritaacaragudang-detail')
        ->assertSeeInOrder([
            'Detail Berita Acara Gudang',
            'Honda Bintaro',
            "Nomor : " . $badetail->nomor . " / " . $badetail->tanggal_berita_acara_formatted,
            'Dibuat oleh ' . $badetail->user_input
        ])
        ->assertSee('Tutup')
        ->assertDontSee('Simpan');

        if ($badetail->tanggal_batal) {
            $response->assertSeeText('Dibatalkan oleh ' . $badetail->user_batal)
                ->assertSee($badetail->keterangan_batal);
        }

        foreach ($badetail->beritaAcaraGudangDetail as $baDetail) {
            $response->assertSee($baDetail->produk->nama)
                ->assertSee($baDetail->kode)
                ->assertSee($baDetail->qty); 
        }

    }

    public function testCanBatalBeritaAcaraGudangKeluar()
    {
       
        $user = $this->getUserRandom('read gudang/berita-acara-gudang');
        $this->actingAs($user);

        $beritaacara = BeritaAcaraGudang::with(['beritaAcaraGudangDetail.produk'])
        ->where([
            ['tanggal_batal', null],
            ['jenis', 'Keluar']
        ])
        ->inRandomOrder()
        ->first();
        $keterangan = $this->faker->sentence(3);

        $expected_stock_fisik = [];
        $expected_stock_free = [];
        $qty = [];

        foreach ($beritaacara->beritaAcaraGudangDetail as $ba) { 

            $expected_stock_fisik[$ba->produk_id] = $ba->produk->stock_fisik + $ba->qty; 
            $expected_stock_free[$ba->produk_id] = $ba->produk->stock_free + $ba->qty; 
            
         }
         $this->put($this->url.'/batal'.'/'.$beritaacara->id, [
            'keterangan' => $keterangan,
        ])
        ->assertOk()
        ->assertJson(['status'=>'success','message' => 'Data berhasil dibatalkan']);

        $actual_berita_acara_gudang = BeritaAcaraGudang::with(['beritaAcaraGudangDetail.produk'])->where('id', $beritaacara->id)->first();

       foreach($actual_berita_acara_gudang->beritaAcaraGudangDetail as $actual_ba){
            $this->assertEquals($expected_stock_fisik[$actual_ba->produk_id], $actual_ba->produk->stock_fisik);
            $this->assertEquals($expected_stock_free[$actual_ba->produk_id], $actual_ba->produk->stock_free);
       }                
       
    }

    public function testCanBatalBeritaAcaraGudangMasuk()
    {
        $user = $this->getUserRandom('read gudang/berita-acara-gudang');
        $this->actingAs($user);

        $beritaacara = BeritaAcaraGudang::with(['beritaAcaraGudangDetail.produk'])
        ->where([
            ['tanggal_batal', null],
            ['jenis', 'Masuk']
        ])
        ->inRandomOrder()
        ->first();
        $keterangan = $this->faker->sentence(3);

        $expected_stock_fisik = [];
        $expected_stock_free = [];
        $qty = [];

        foreach ($beritaacara->beritaAcaraGudangDetail as $ba) { 

            $expected_stock_fisik[$ba->produk_id] = $ba->produk->stock_fisik - $ba->qty; 
            $expected_stock_free[$ba->produk_id] = $ba->produk->stock_free - $ba->qty; 
            
         }
         $this->put($this->url.'/batal'.'/'.$beritaacara->id, [
            'keterangan' => $keterangan,
        ])
        ->assertOk()
        ->assertJson(['status'=>'success','message' => 'Data berhasil dibatalkan']);

        $actual_berita_acara_gudang = BeritaAcaraGudang::with(['beritaAcaraGudangDetail.produk'])->where('id', $beritaacara->id)->first();

       foreach($actual_berita_acara_gudang->beritaAcaraGudangDetail as $actual_ba){
            $this->assertEquals($expected_stock_fisik[$actual_ba->produk_id], $actual_ba->produk->stock_fisik);
            $this->assertEquals($expected_stock_free[$actual_ba->produk_id], $actual_ba->produk->stock_free);
       }
    }

    public function testValidationStoreWithoutChooseProduk()
    {
        $user = $this->getUserRandom('read gudang/berita-acara-gudang');
        $this->actingAs($user);

        $this->postJson($this->url)
        ->assertJsonStructure([
            'message', 'errors'
        ]);

        $tanggal_berita_acara = now()->format('d-m-Y');
        $keterangan = $this->faker->sentence(3);

        $this->post($this->url, [
            'tanggal_berita_acara'=>$tanggal_berita_acara,
            'jenis'=>'Keluar',
            'keterangan'=>$keterangan,
           ])
           ->assertOk()
           ->assertJson(['status'=>'error','message' => 'Belum memilih produk']);

    }

    public function testQtyCannotBiggerThanStock()
    {
        $user = $this->getUserRandom('read gudang/berita-acara-gudang');
       $this->actingAs($user);

       $tanggal_berita_acara = now()->format('d-m-Y');
       $keterangan = $this->faker->sentence(3);
       $produk = Produk::where('stock_free', '<', 50)->orWhere('stock_fisik', '<', 50)->limit(random_int(1,9))->get();
       $qty = [];
       $keterangan_produk= [];
       $expected_stock_fisik = [];
       $expected_stock_free = [];
       $expected_detail = [];
       foreach ($produk as $p) {
            $qty[$p->id] = 50;
            $keterangan_produk[$p->id] = $this->faker->sentence(3);

            $expected_stock_fisik[$p->id] = $p->stock_fisik - $qty[$p->id]; 
            $expected_stock_free[$p->id] = $p->stock_free - $qty[$p->id]; 
            
            $expected_detail[$p->id] = [
                'qty' => $qty[$p->id],
                'keterangan' => $keterangan_produk[$p->id]
        ];

       }

       $this->post($this->url, [
        
        'tanggal_berita_acara'=>$tanggal_berita_acara,
        'jenis'=>'Keluar',
        'keterangan'=>$keterangan,
        'qty' =>$qty,
        'keterangan_produk' => $keterangan_produk
        
       ])
       ->assertOk()
       ->assertJson(['status'=>'error','message' => 'Gagal, qty melebihi stock produk '. $produk->first()->kode .': '.$produk->first()->stock_free]);
        
    }

    public function testCannotStoreProdukDosntExist()
    {
        $user = $this->getUserRandom('read gudang/berita-acara-gudang');
        $this->actingAs($user);
 
        $tanggal_berita_acara = now()->format('d-m-Y');
        $keterangan = $this->faker->sentence(3);
       
 
        $this->post($this->url, [
         
         'tanggal_berita_acara'=>$tanggal_berita_acara,
         'jenis'=>'Keluar',
         'keterangan'=>$keterangan,
         'qty' => [
             'rtr'=>5
         ],
         'keterangan_produk' => [
            'gghgh'=>'Test'
         ],
         
        ])
        ->assertOk()
        ->assertJson(['status'=>'error','message' => 'Data produk tidak tersedia']);
    }
}

