<?php

namespace App\Models\MasterData;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merek extends Model
{
    use HasFactory;
    protected $table = 'data_merek';
    protected $guarded = ['id','tanggal'];
    public $timestamps = false;

    public function foto()
    {
        return $this->morphOne(Foto::class, 'referensi');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('aktif',1);
    }
}
