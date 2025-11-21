<?php

namespace App\Models\Pembelian;

use App\Models\MasterData\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananProdukDetail extends Model
{
    use HasFactory;

    protected $table = 'trans_pemesanan_produk_detail';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function scopeSelesaiPenerimaan($query, $selesai = true)
    {
        $query->where('penerimaan', $selesai);
    }

    public function pemesanan()
    {
        return $this->belongsTo(PemesananProduk::class, 'pemesanan_produk_id', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }

    public function getQtyFormattedAttribute()
    {
        if ($this->diskon !== null) return numberFormat($this->qty);
    }

    public function getDiskonFormattedAttribute()
    {
        if ($this->diskon !== null) return numberFormat($this->diskon);
    }

    public function getSubTotalFormattedAttribute()
    {
        if ($this->sub_total) return numberFormat($this->sub_total);
    }
}
