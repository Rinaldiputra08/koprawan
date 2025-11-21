<?php

namespace App\Models\Penjualan;

use App\Models\MasterData\Karyawan;
use App\Models\MasterData\Produk;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'trans_cart';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function produk()
    {
        return $this->morphTo();
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('terjual', 0);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
