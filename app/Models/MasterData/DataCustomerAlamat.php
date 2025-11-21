<?php

namespace App\Models\MasterData;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCustomerAlamat extends Model
{
    use HasFactory;
    protected $table = 'data_customer_alamat';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo(DataCustomer::class,'data_customer_id','id');
    }

    public function getTanggalAttribute($value)
    {
        return Carbon::create($value)->format('d-m-Y H:i:s');
    }
}
