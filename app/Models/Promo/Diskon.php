<?php

namespace App\Models\Promo;

use App\Models\MasterData\Produk;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskon extends Model
{
    use HasFactory;
    protected $table = 'data_diskon';
    protected $guarded = ['id', 'tanggal'];
    public $timestamps = false;

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function scopeBerlaku(Builder $query)
    {
        $query->where('tanggal_awal', '<=', now())->where('tanggal_akhir','>=', now());
    }

    public function setNominalAttribute($value)
    {
        $this->attributes['nominal'] = str_replace('.', '', $value);
    }

    public function getTanggalAwalBerlakuFormattedAttribute()
    {
        if ($this->tanggal_awal) return Carbon::create($this->tanggal_awal)->format('d-m-Y');
    }

    public function getTanggalAkhirBerlakuFormattedAttribute()
    {
        if ($this->tanggal_awal) return Carbon::create($this->tanggal_awal)->format('d-m-Y');
    }

    public function getTanggalAkhirFormattedAttribute()
    {
        if ($this->tanggal_akhir) return Carbon::create($this->tanggal_akhir)->format('d-m-Y H:i:s');
    }

    public function getTanggalAwalFormattedAttribute()
    {
        if ($this->tanggal_awal) return Carbon::create($this->tanggal_awal)->format('d-m-Y H:i:s');
    }

    public function getJamAwalAttribute()
    {
        if($this->tanggal_awal) return Carbon::create($this->tanggal_awal)->format('H');
    }

    public function getJamAkhirAttribute()
    {
        if($this->tanggal_akhir) return Carbon::create($this->tanggal_akhir)->format('H');
    }

    public function getMenitAwalAttribute()
    {
        if($this->tanggal_awal) return Carbon::create($this->tanggal_awal)->format('i');
    }

    public function getMenitAkhirAttribute()
    {
        if($this->tanggal_akhir) return Carbon::create($this->tanggal_akhir)->format('i');
    }

    public function setTanggalAwalAttribute($value)
    {
        $this->attributes['tanggal_awal'] = Carbon::create($value)->format('Y-m-d H:i:s');
    }

    public function setTanggalAkhirAttribute($value)
    {
        $this->attributes['tanggal_akhir'] = Carbon::create($value)->format('Y-m-d H:i:s');
    }

    public function getNominalFormattedAttribute()
    {
        return number_format($this->nominal, 0, ',', '.');
    }
}
