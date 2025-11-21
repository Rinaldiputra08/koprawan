<?php

namespace App\Models;

use App\Models\MasterData\Voucher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransVoucher extends Model
{
    use HasFactory;
    protected $table = 'trans_voucher';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function scopeBatal($query, $batal = true)
    {
        $query->where('batal', $batal);
    }
}
