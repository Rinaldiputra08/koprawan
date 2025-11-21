<?php

namespace App\Models\Gudang;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcaraGudang extends Model
{
    use HasFactory;
    protected $table = 'berita_acara_gudang';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function scopeBatal(Builder $query, $batal = true)
    {
        if ($batal) {
            $query->whereNotNull('tanggal_batal');
        }
        $query->whereNull('tanggal_batal');
    }

    public function beritaAcaraGudangDetail()
    {
        return $this->hasMany(BeritaAcaraGudangDetail::class, 'berita_acara_gudang_id');
    }

    public function setTanggalBeritaAcaraAttribute($value)
    {
        $this->attributes['tanggal_berita_acara'] = Carbon::create($value)->format('Y-m-d');
    }

    public function getTanggalBeritaAcaraFormattedAttribute()
    {
        if ($this->tanggal_berita_acara) return Carbon::create($this->tanggal_berita_acara)->format('d-m-Y');
    }
}



