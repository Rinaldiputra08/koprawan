<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    use HasFactory;
    protected $table = 'data_foto';
    protected $guarded = ['id', 'tanggal'];
    public $timestamps = false;

    public function referensi()
    {
        return $this->morphTo();
    }

    public function scopeThumbnail($query)
    {
        return $query->where('thumbnail', 1);
    }

}
