<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimitKaryawan extends Model
{
    use HasFactory;
    protected $table = "limit_karyawan";
    protected $guarded = ['id'];
    public $timestamps = false;


    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function scopeActive(Builder $query)
    {
        $periode = getCurrentPeriode();

        return $query->where('periode', $periode);
    }

    public function getNominalFormattedAttribute()
    {
        return number_format($this->nominal, 0, ',', '.');
    }
}
