<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'data_supplier';
    protected $guarded = ['id', 'tangga'];
    public $timestamps = false;

    public function scopeActive(Builder $query)
    {
        return $query->where('aktif', 1);
    }
}
