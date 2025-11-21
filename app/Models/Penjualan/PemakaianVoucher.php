<?php

namespace App\Models\Penjualan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemakaianVoucher extends Model
{
    use HasFactory;
    protected $table = 'trans_voucher';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }
}
