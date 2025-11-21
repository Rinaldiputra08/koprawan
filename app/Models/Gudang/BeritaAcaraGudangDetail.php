<?php

namespace App\Models\Gudang;

use App\Models\MasterData\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcaraGudangDetail extends Model
{
    use HasFactory;
    protected $table = 'berita_acara_gudang_detail';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function beritaAcaraGudang()
    {
        return $this->belongsTo(BeritaAcaraGudang::class, 'berita_acara_gudang_id', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
