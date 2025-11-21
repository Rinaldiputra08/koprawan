<?php

namespace App\Models\Penjualan;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    use HasFactory;
    protected $table = 'trans_retur_penjualan';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function setTanggalReturnAttribute($value)
    {
        $this->attributes['tanggal_return'] = Carbon::create($value)->format('Y-m-d');
    }

    public function getTanggalReturnFormattedAttribute()
    {
        if ($this->tanggal_return) return Carbon::create($this->tanggal_return)->format('d-m-Y');
    }
}
