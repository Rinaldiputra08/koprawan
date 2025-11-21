<?php

namespace Tests\Feature;

use App\Models\MasterData\VoucherKriteria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class VoucherKriteriaTest extends TestCase
{
    private $url = '/master-data/voucher-kriteria';
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    private function user($role)
    {
        return User::role($role)->where('aktif', '1')->inRandomOrder()->first();
    }

    public function testCanShowPageVoucherKriteria()
    {
        $user = $this->user('administrator');
        $this->actingAs($user);
        $this->get($this->url)
            ->assertStatus(200);
    }

    public function testCannotShowPageVoucherKriteriaNotLogin()
    {
        $this->get($this->url)
            ->assertRedirect('/')
            ->assertStatus(302);
    }

    public function testCannotShowVoucherKriteriaUnauthorized()
    {
        $user = $this->user('user');
        $this->ActingAs($user);
        $this->get($this->url)
            ->assertStatus(403);
    }

    public function testCanCreateVoucherKriteria()
    {
        $user = $this->user('administrator');
        $data = new VoucherKriteria();
        $this->actingAs($user);
        $this->get($this->url . '/create')
            ->assertStatus(200)
            ->assertSeeText('Tambah Voucher Kriteria')
            ->assertViewHas('data', $data);
    }

    public function testCannotCreateVoucherKriteriaUnauthorized()
    {
        $user = $this->user('user');
        $this->actingAs($user);
        $this->get($this->url . '/create')
            ->assertStatus(403);
    }

    public function testCannotCreateVoucherKriteriaNotLogin()
    {
        $this->get($this->url . '/create')
            ->assertStatus(302)
            ->assertRedirect('/');
    }

    public function testCanStoreVoucherKriteria()
    {
        $user = $this->user('administrator');
        $this->actingAs($user);

        $this->post($this->url, [
            'nama' => $this->faker->randomLetter,
            'nominal' => '2000'
        ])
            ->assertOk()
            ->assertJson(['status' => 'success', 'message' => 'Data berhasil disimpan']);
        // DB::rollBack();
    }
    public function testValidationVoucherKriteriaRequired()
    {
        $user = $this->user('administrator');
        $this->actingAs($user);
        $this->postJson($this->url)
            ->assertJsonStructure([
                'message', 'errors'
            ])
            ->assertJsonValidationErrors([
                'nama',
                'nominal',
            ])
            ->assertStatus(422);
    }

    public function testEditVoucherkriteria()
    {
        $user = $this->user('administrator');
        $data = VoucherKriteria::inRandomOrder()->first();
        $this->actingAs($user);
        $this->get($this->url . '/' . $data->id . '/edit')
            ->assertStatus(200)
            ->assertSeeText('Edit Voucher Kriteria')
            ->assertViewHas('data', $data);
    }

    public function testUpdateVoucherKriteria()
    {
        $user = $this->user('administrator');
        $data = VoucherKriteria::inRandomOrder()->first();
        $this->actingAs($user);
        $nama_ubah = $this->faker->randomLetter;
        $nominal = $this->faker->numberBetween(1000, 90000);
        $this->put($this->url . '/' . $data->id, [
            'nama' => $nama_ubah,
            'nominal' => $nominal
        ])
            ->assertOk();

        $real = VoucherKriteria::find($data->id);

        $this->assertEquals($nama_ubah, $real->nama);
        $this->assertEquals($nominal, $real->nominal);
    }
}
