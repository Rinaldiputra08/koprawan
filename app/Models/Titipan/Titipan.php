<?php

namespace App\Models\Titipan;

use App\Models\MasterData\Foto;
use App\Models\MasterData\Karyawan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Titipan extends Model
{
    use HasFactory;
    protected $table = 'data_titipan';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function foto()
    {
        return $this->morphMany(Foto::class, 'referensi');
    }

    public function fotoThumbnail()
    {
        return $this->morphOne(Foto::class, 'referensi')->thumbnail();
    }

    public function scopeActive(Builder $query)
    {
        $query->where([
            ['tanggal_awal_penjualan', '<=', now()],
            ['tanggal_akhir_penjualan', '>=', now()]
        ]);
    }

    function getHargaJualFormattedAttribute()
    {
        if ($this->harga_jual) return number_format($this->harga_jual, 0, ',', '.');
    }

    public function penjualan()
    {
        return $this->morphMany(PenjualanDetail::class, 'produk');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function getTanggalFormattedAttribute()
    {
        if ($this->tanggal) return Carbon::create($this->tanggal)->format('d-m-Y H:i:s');
    }
}
