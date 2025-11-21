<?php

namespace App\Models\Penjualan;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    use HasFactory;
    protected $table = 'trans_piutang';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function getNominalFormattedAttribute()
    {
        if ($this->nominal !== null) return numberFormat($this->nominal);
    }
}
