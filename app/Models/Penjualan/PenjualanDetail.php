<?php

namespace App\Models\Penjualan;

use App\Models\MasterData\Produk;
use App\Models\Penjualan\Penjualan;
use App\Models\Penjualan\SerahTerimaBarang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenjualanDetail extends Model
{
    use HasFactory;
    protected $table = 'trans_penjualan_detail';
    protected $guarded = ['id'];
    public $timestamps = false;


    public function produk()
    {
        return $this->morphTo();
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

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function serahTerima()
    {
        return $this->hasOne(SerahTerimaBarang::class, 'penjualan_id');
    }


}