<?php

namespace Tests\Feature;

use App\Models\MasterData\Voucher;
use App\Models\MasterData\VoucherKriteria;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;



class VoucherTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    private $url = '/master-data/voucher';
    use WithFaker;

    public function setUp():void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    public function user($role)
    {
        return User::role($role)->where('aktif', '1')->inRandomOrder()->first();
    }

    public function testShowPageVoucher()
    {
      $user = $this->user('administrator');
      $this->actingAs($user);
      $this->get($this->url)
      ->assertOk();
    }

    public function testShowPageVoucherNotLogin()
    {
        $this->get($this->url)
        ->assertRedirect('/')
        ->assertStatus(302);
    }

    public function testShowPageVoucherUnauthorized()
    {
        $user = $this->user('user');
        $this->actingAs($user);
        $this->get($this->url)
        ->assertStatus(403);
    }

    public function testCanCreateVoucher()
    {
        $user = $this->user('administrator');
        $data = new Voucher();
        $this->actingAs($user);
        $this->get($this->url.'/create')
        ->assertSeeText('Tambah Voucher')
        ->assertStatus(200)
        ->assertViewHas('data', $data);
    }

    public function testCannotCreateVoucherUnauthorized()
    {
        $user = $this->user('user');
        $this->actingAs($user);
        $this->get($this->url . '/create')
        ->assertStatus(403);

    }

    public function tesCreateVoucherNotLogin()
    {
        $this->get($this->url.'/create')
        ->assertRedirect('/')
        ->assertStatus(403);
    }

    public function testCanStoreVoucherWithoutKetentuan()
    {
        $user = $this->user('administrator');
        $this->actingAs($user);
        $nama_ubah = $this->faker->sentence(3);
        $tanggal_awal = now()->format('d-m-Y');
        $tanggal_akhir = now()->addDays(10)->format('d-m-Y');
        $jam_awal = now()->format('H');
        $menit_awal = now()->format('i');
        $jam_akhir = now()->format('H');
        $menit_akhir = now()->format('i');
        $this->post($this->url, [
            'nama' => $nama_ubah,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'jam_awal' => $jam_awal,
            'jam_akhir' => $jam_akhir,
            'menit_awal' => $menit_awal,
            'menit_akhir' => $menit_akhir,
            'has_kriteria'=> 0,
            'nominal'=>'1000',
            'jenis'=>'Voucher umum'

        ])
         ->assertOk()
        ->assertJson(['status'=>'success','message' => 'Data berhasil disimpan']);

    }

    public function testCanStoreVoucherWithKetentuan()
    {
        
        $user = $this->user('administrator');
        $this->actingAs($user);
        $tanggal_awal = now()->format('d-m-Y');
        $tanggal_akhir = now()->addDays(10)->format('d-m-Y');
        $jam_awal = now()->format('H');
        $menit_awal = now()->format('i');
        $jam_akhir = now()->format('H');
        $menit_akhir = now()->format('i');
        $get_kriteria = VoucherKriteria::pluck('id')->toArray();
        $this->post($this->url, [
            'nama' => $this->faker->randomLetter,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'jam_awal' => $jam_awal,
            'jam_akhir' => $jam_akhir,
            'menit_awal' => $menit_awal,
            'menit_akhir' => $menit_akhir,
            'has_kriteria'=> 1,
            'kriteria'=> $get_kriteria,
            'nominal'=>'1000',
            'jenis'=>'Voucher umum'

        ])
         ->assertOk()
        ->assertJson(['status'=>'success','message' => 'Data berhasil disimpan']);

    }

    public function testEditVoucher()
    {
        $user = $this->user('administrator');
        $data = Voucher::inRandomOrder()->first();
        $this->actingAs($user);
        $this->get($this->url.'/'.$data->id.'/edit')
        ->assertSeeText('Edit Voucher')
        ->assertViewHas('data', $data);
    }

    public function testUpdateVoucher()
    {
        $user = $this->user('administrator');
        $data = Voucher::inRandomOrder()->first();
        $this->actingAs($user);
        $nama_ubah = $this->faker->sentence(3);
        $nominal = $this->faker->numberBetween(1000, 90000);
        $tanggal_awal = now()->format('d-m-Y');
        $tanggal_akhir = now()->addDays(10)->format('d-m-Y');
        $jam_awal = now()->format('H');
        $menit_awal = now()->format('i');
        $jam_akhir = now()->format('H');
        $menit_akhir = now()->format('i');
        $this->put($this->url.'/'.$data->id, [
            'nama' => $nama_ubah,
            'nominal' => $nominal,
            'ketentuan' => 1,
            'jenis'=>'voucher umum',
            'tanggal_awal'=>$tanggal_awal,
            'tanggal_akhir'=>$tanggal_akhir,
            'jam_awal'=>$jam_awal,
            'jam_akhir'=>$jam_akhir,
            'menit_awal'=>$menit_awal,
            'menit_akhir'=>$menit_akhir,
            'has_kriteria'=>0,

        ])
        ->assertOk();

        $real = Voucher::find($data->id);
        $tanggal_awal = convertDate($tanggal_awal).' '.$jam_awal.':'.$menit_awal.':00';
        $tanggal_akhir = convertDate($tanggal_akhir).' '.$jam_akhir.':'.$menit_akhir.':00';
        $this->assertEquals($nama_ubah, $real->nama);
        $this->assertEquals($nominal, $real->nominal);
        $this->assertEquals($tanggal_awal, $real->tanggal_awal);
        $this->assertEquals($tanggal_akhir, $real->tanggal_akhir);
    }
    public function testValidationVoucherRequired()
    {
       $user = $this->user('administrator');
        $this->actingAs($user);
        $this->postJson($this->url)
            ->assertJsonStructure([
                'message', 'errors'
            ])
        ->assertJsonValidationErrors([
            'nama',
            'tanggal_awal',
            'tanggal_akhir',
            'jenis',
            'nominal',
            'jenis',
            'has_kriteria', 

        ])
        ->assertStatus(422);
    }
}
