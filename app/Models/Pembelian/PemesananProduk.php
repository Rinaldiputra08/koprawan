<?php

namespace App\Models\Pembelian;

use App\Models\MasterData\Produk;
use App\Models\MasterData\Supplier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananProduk extends Model
{
    use HasFactory;

    protected $table = 'trans_pemesanan_produk';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function scopeBatal(Builder $query, $batal = true)
    {
        if ($batal) {
            $query->whereNotNull('tanggal_batal');
        }
        $query->whereNull('tanggal_batal');
    }

    public function scopeSelesaiPenerimaan($query, $selesai = true)
    {
        $query->where('penerimaan', $selesai);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function penerimaanProduk()
    {
        return $this->hasMany(PenerimaanProduk::class, 'pemesanan_produk_id')->batal(false);
    }

    public function riwayatPenerimaanProduk()
    {
        return $this->hasMany(PenerimaanProduk::class, 'pemesanan_produk_id');
    }

    public function pemesananDetail()
    {
        return $this->hasMany(PemesananProdukDetail::class, 'pemesanan_produk_id', 'id');
    }

    public function setTanggalPemesananAttribute($value)
    {
        $this->attributes['tanggal_pemesanan'] = Carbon::create($value)->format('Y-m-d');
    }

    public function getTanggalPemesananFormattedAttribute()
    {
        if ($this->tanggal_pemesanan) return Carbon::create($this->tanggal_pemesanan)->format('d-m-Y');
    }

    public function getTanggalBatalFormattedAttribute()
    {
        if ($this->tanggal_batal) return Carbon::create($this->tanggal_batal)->format('d-m-Y');
    }

    public function getTotalFormattedAttribute()
    {
        if ($this->total !== null) return numberFormat($this->total);
    }

    public function getPpnFormattedAttribute()
    {
        if ($this->ppn !== null) return numberFormat($this->ppn);
    }
}
