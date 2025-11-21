<?php

namespace App\Models\MasterData;

use App\Models\ExpiredFaktur;
use App\Models\Pembelian\PenerimaanProdukDetail;
use App\Models\Penjualan\Cart;
use App\Models\Penjualan\PenjualanDetail;
use App\Models\Promo\Diskon;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'data_produk';
    protected $guarded = ['id', 'tanggal'];
    public $timestamps = false;

    public function diskon()
    {
        return $this->hasOne(Diskon::class)->berlaku();
    }

    public function carts()
    {
        return $this->morphMany(Cart::class, 'produk');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('aktif', 1);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function foto()
    {
        return $this->morphMany(Foto::class, 'referensi');
    }

    public function fotoThumbnail()
    {
        return $this->morphOne(Foto::class, 'referensi')->thumbnail();
    }

    public function merek()
    {
        return $this->belongsTo(Merek::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    function getHargaBeliFormattedAttribute()
    {
        return number_format($this->harga_beli, 0, ',', '.');
    }

    function getHargaJualFormattedAttribute()
    {
        return number_format($this->harga_jual, 0, ',', '.');
    }

    function getHppSebelumFormattedAttribute()
    {
        return number_format($this->hpp_sebelum, 0, ',', '.');
    }

    function getHppFormattedAttribute()
    {
        return number_format($this->hpp, 0, ',', '.');
    }

    function getStockFreeFormattedAttribute()
    {
        return number_format($this->stock_free, 0, ',', '.');
    }

    function getStockFisikFormattedAttribute()
    {
        return number_format($this->stock_fisik, 0, ',', '.');
    }

    public function getTanggalBeliAkhirFormattedAttribute()
    {
        if ($this->tanggal_beli_akhir) return Carbon::create($this->tanggal_beli_akhir)->format('d-m-Y');
    }

    public function getTanggalJualAkhirFormattedAttribute()
    {
        if ($this->tanggal_jual_akhir) return Carbon::create($this->tanggal_jual_akhir)->format('d-m-Y');
    }

    public function setHargaBeliAttribute($value)
    {
        $this->attributes['harga_beli'] = str_replace('.', '', $value);
    }

    public function setHargaJualAttribute($value)
    {
        $this->attributes['harga_jual'] = str_replace('.', '', $value);
    }

    public function getTanggalFormattedAttribute()
    {
        if ($this->tanggal) return Carbon::create($this->tanggal)->format('d-m-Y H:i:s');
    }

    public function getAktifStringAttribute()
    {
        return $this->aktif == 1 ? 'Y' : 'N';
    }

    public function penjualan()
    {
        return $this->morphMany(PenjualanDetail::class, 'produk');
    }

    public function penerimaan()
    {
        return $this->hasMany(PenerimaanProdukDetail::class, 'produk_id');
    }

    public function rating()
    {
        return $this->morphMany(Rating::class, 'produk');
    }
}
