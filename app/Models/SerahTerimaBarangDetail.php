<?php

namespace App\Models;

use App\Models\MasterData\Produk;
use App\Models\Penjualan\Penjualan;
use Illuminate\Database\Eloquent\Model;
use App\Models\Penjualan\SerahTerimaBarang;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SerahTerimaBarangDetail extends Model
{
    use HasFactory;
    protected $table = 'trans_serah_terima_detail';
    protected $guarded = ['id'];
    public $timestamps = false;


    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function serahterima()
    {
        return $this->belongsTo(SerahTerimaBarang::class, 'serah_terima_id');
    }

    public function getQtyFormattedAttribute()
    {
        return numberFormat($this->qty);
    }

    public function getDiskonFormattedAttribute()
    {
        return numberFormat($this->diskon);
    }

    public function getGrandTotalFormattedAttribute()
    {
        return numberFormat($this->grand_total);
    }
}