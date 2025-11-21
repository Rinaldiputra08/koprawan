<?php

namespace App\Models\Closing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosingProdukApproval extends Model
{
    use HasFactory;
    protected $table = 'closing_produk_approval';
    protected $guarded = ['id'];
    public $timestamps = false;
}
