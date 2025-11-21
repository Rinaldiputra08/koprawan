<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $table = 'produk_rating';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function produk()
    {
        return $this->morphTo('produk');
    }
}
