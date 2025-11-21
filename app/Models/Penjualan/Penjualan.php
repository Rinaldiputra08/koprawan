<?php

namespace App\Models\Penjualan;

use App\Models\MasterData\Karyawan;
use App\Models\Penjualan\PenjualanDetail;
use App\Models\Penjualan\SerahTerimaBarang;
use App\Models\TransVoucher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Penjualan\Retur;
use App\Models\SerahTerimaBarangDetail;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'trans_penjualan';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function retur()
    {
        return $this->hasOne(Retur::class, 'penjualan_id');
    }

    public function penjualanDetail()
    {
        return $this->hasMany(PenjualanDetail::class, 'penjualan_id');
    }

    public function serahTerima()
    {
        return $this->hasOne(SerahTerimaBarang::class, 'penjualan_id', 'id');
    }

    public function getTotalFormattedAttribute()
    {
        if ($this->total !== null) return numberFormat($this->total);
    }

    public function getDiskonFormattedAttribute()
    {
        if ($this->diskon !== null) return numberFormat($this->diskon);
    }

    public function voucher()
    {
        return $this->hasMany(TransVoucher::class, 'penjualan_id');
    }

    public function getTanggalFormattedAttribute()
    {
        if ($this->tanggal) return Carbon::create($this->tanggal)->format('d-m-Y');
    }

    public function getGrandTotalFormattedAttribute()
    {
        return numberFormat($this->grand_total);
    }

    public function scopeBatal(Builder $query,  $batal = true)
    {
        return $query->where('batal', $batal);
    }
}
