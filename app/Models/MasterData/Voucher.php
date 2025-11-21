<?php

namespace App\Models\MasterData;

use App\Models\Penjualan\PemakaianVoucher;
use App\Models\Penjualan\Penjualan;
use App\Models\TransVoucher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Voucher extends Model
{
    use HasFactory;
    protected $table = 'data_voucher';
    protected $guarded = ['id', 'tanggal'];
    public $timestamps = false;

    public function scopeBerlaku($query)
    {
        return $query->where('tanggal_awal', '<=', now())
            ->where('tanggal_akhir', '>=', now());
    }

    public function setNominalAttribute($value)
    {
        $this->attributes['nominal'] = str_replace('.', '', $value);
    }

    public function getNominalFormattedAttribute()
    {
        return number_format($this->nominal, 0, ',', '.');
    }

    public function getTanggalAkhirFormattedAttribute()
    {
        if ($this->tanggal_akhir) return Carbon::create($this->tanggal_akhir)->format('d-m-Y');
    }

    public function getTanggalAwalFormattedAttribute()
    {
        if ($this->tanggal_awal) return Carbon::create($this->tanggal_awal)->format('d-m-Y');
    }

    public function setTanggalAwalAttribute($value)
    {
        $this->attributes['tanggal_awal'] = Carbon::create($value)->format('Y-m-d H:i:s');
    }

    public function setTanggalAkhirAttribute($value)
    {
        $this->attributes['tanggal_akhir'] = Carbon::create($value)->format('Y-m-d H:i:s');
    }

    public function kriteria()
    {
        return $this->belongsToMany(VoucherKriteria::class, 'kriteria_voucher', 'voucher_id', 'kriteria_id');
    }

    public function getJamAwalAttribute()
    {
        if ($this->tanggal_awal) return Carbon::create($this->tanggal_awal)->format('H');
    }

    public function getJamAkhirAttribute()
    {
        if ($this->tanggal_akhir) return Carbon::create($this->tanggal_akhir)->format('H');
    }

    public function getMenitAwalAttribute()
    {
        if ($this->tanggal_awal) return Carbon::create($this->tanggal_awal)->format('i');
    }

    public function getMenitAkhirAttribute()
    {
        if ($this->tanggal_akhir) return Carbon::create($this->tanggal_akhir)->format('i');
    }

    public function penerimaVoucher()
    {
        return $this->belongsToMany(Karyawan::class);
    }

    public function pemakaian()
    {
        return $this->hasMany(TransVoucher::class, 'voucher_id');
    }
}
