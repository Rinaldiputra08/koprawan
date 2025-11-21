<?php

namespace App\Models\Pembelian;

use App\Models\MasterData\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanProdukDetail extends Model
{
    use HasFactory;

    protected $table = 'trans_penerimaan_produk_detail';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function penerimaan()
    {
        return $this->belongsTo(PenerimaanProduk::class, 'penerimaan_produk_id', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function getQtyFormattedAttribute()
    {
        return numberFormat($this->qty);
    }

    public function getDiskonFormattedAttribute()
    {
        return numberFormat($this->diskon);
    }

    public function getSubTotalFormattedAttribute()
    {
        return numberFormat($this->sub_total);
    }
}
