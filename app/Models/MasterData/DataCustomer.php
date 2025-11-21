<?php

namespace App\Models\MasterData;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCustomer extends Model
{
    use HasFactory;
    protected $table = 'data_customer';
    protected $guarded = ['id', 'tanggal'];
    public $timestamps = false;

    public function alamat()
    {
        return $this->hasMany(DataCustomerAlamat::class,'customer_id');
    }

    public function alamatUtama()
    {
        return $this->hasOne(DataCustomerAlamat::class,'customer_id')->utama();
    }

    public function getTanggalAttribute($value)
    {
        return Carbon::create($value)->format('d-m-Y H:i:s');
    }

    public function getTanggaLahirAttribut($value)
    {
        return Carbon::create($value)->format('d-m-Y H:i:s');
    }
}
