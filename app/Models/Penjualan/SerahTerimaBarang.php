<?php

namespace App\Models\Penjualan;

use Carbon\Carbon;
use App\Models\MasterData\Produk;
use App\Models\MasterData\Karyawan;
use App\Models\Penjualan\Penjualan;

use App\Models\SerahTerimaBarangDetail;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Penjualan\PenjualanLangsungDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SerahTerimaBarang extends Model
{
    use HasFactory;

    protected $table = 'trans_serah_terima';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function serahTerimaDetail()
    {
        return $this->hasMany(SerahTerimaBarangDetail::class, 'serah_terima_id');
    }

    public function getTotalFormattedAttribute()
    {
        if ($this->total !== null) return numberFormat($this->total);
    }

    public function getDiskonFormattedAttribute()
    {
        if ($this->diskon !== null) return numberFormat($this->diskon);
    }

    // public function voucher()
    // {
    //     return $this->hasMany(TransVoucher::class, 'penjualan_id');
    // }

    public function getTanggalFormattedAttribute()
    {
        if ($this->tanggal) return Carbon::create($this->tanggal)->format('d-m-Y');
    }

    public function getGrandTotalFormattedAttribute()
    {
        return numberFormat($this->grand_total);
    }

    // public function scopeBatal(Builder $query,  $batal = true)
    // {
    //     return $query->where('batal', $batal);
    // }

    
}