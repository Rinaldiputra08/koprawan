<?php

namespace App\Models\MasterData;

use App\Models\Penjualan\Cart;
use App\Models\Penjualan\PemakaianVoucher;
use App\Models\Penjualan\Piutang;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Karyawan extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $table = 'data_karyawan';
    protected $guarded = ['id'];
    protected $hidden = ['password'];
    public $timestamps = false;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function carts()
    {
        return $this->hasMany(Cart::class,'karyawan_id');
    }

    public function piutang()
    {
        return $this->hasOne(Piutang::class, 'karyawan_id');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('aktif', 1);
    }

    public function voucher()
    {
        return $this->belongsToMany(Voucher::class);
    }

    public function pemakaianVoucher()
    {
        return $this->hasMany(PemakaianVoucher::class, 'karyawan_id');
    }

    public function limit()
    {
        return $this->hasOne(LimitKaryawan::class, 'karyawan_id');
    }
    public function foto()
    {
        return $this->morphOne(Foto::class, 'referensi');
    }
}
