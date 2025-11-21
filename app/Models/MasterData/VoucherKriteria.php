<?php

namespace App\Models\MasterData;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class VoucherKriteria extends Model
{
    use HasFactory;
    protected $table = 'data_voucher_kriteria';
    protected $guarded = ['id', 'tanggal'];
    public $timestamps = false;

    public function setNominalAttribute($value)
    {
        $this->attributes['nominal'] = str_replace('.', '', $value);
    }

    public function getNominalFormattedAttribute()
    {
        return number_format($this->nominal, 0, ',', '.');
    }

    public function getTanggalFormattedAttribute()
    {
        if ($this->tanggal) return Carbon::create($this->tanggal)->format('d-m-Y H:i:s');
    }

    public function setTanggalAttribute($value)
    {
        $this->attributes['tanggal'] = Carbon::create($value)->format('Y-m-d H:i:s');
    }

    
}


