<?php

namespace App\Models\Closing;

use App\Models\MasterData\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosingProduk extends Model
{
    use HasFactory;

    protected $table = 'closing_produk';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
