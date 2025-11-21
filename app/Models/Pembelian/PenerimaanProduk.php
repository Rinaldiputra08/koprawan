<?php

namespace App\Models\Pembelian;

use App\Models\MasterData\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PenerimaanProduk extends Model
{
    use HasFactory;

    protected $table = 'trans_penerimaan_produk';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function scopeBatal(Builder $query, $batal = true)
    {
        if ($batal) {
            $query->whereNotNull('tanggal_batal');
        }
        $query->whereNull('tanggal_batal');
    }

    public function penerimaanDetail()
    {
        return $this->hasMany(PenerimaanProdukDetail::class, 'penerimaan_produk_id', 'id');
    }

    public function pemesanan()
    {
        return $this->belongsTo(PemesananProduk::class, 'pemesanan_produk_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function setTanggalPenerimaanAttribute($value)
    {
        $this->attributes['tanggal_penerimaan'] = Carbon::create($value)->format('Y-m-d');
    }

    public function setTanggalTagihanAttribute($value)
    {
        $this->attributes['tanggal_tagihan'] = Carbon::create($value)->format('Y-m-d');
    }

    public function getTanggalPenerimaanFormattedAttribute()
    {
        if ($this->tanggal_penerimaan) return Carbon::create($this->tanggal_penerimaan)->format('d-m-Y');
    }

    public function getTanggalTagihanFormattedAttribute()
    {
        if ($this->tanggal_tagihan) return Carbon::create($this->tanggal_tagihan)->format('d-m-Y');
    }

    public function getTotalFormattedAttribute()
    {
        return numberFormat($this->total);
    }

    public function getPpnFormattedAttribute()
    {
        return numberFormat($this->ppn);
    }

    public function getGrandTotalAttribute()
    {
        return numberFormat($this->total + $this->ppn);
    }
}
