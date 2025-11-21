<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetupApplication extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps = false;

    public function getValueJsonAttribute()
    {
        if (is_string($this->value) and json_decode($this->value) and json_last_error() == JSON_ERROR_NONE and !in_array($this->value, ['1', '0'])) {
            return json_decode($this->value);
        }
    }

    public function getValueIntAttribute()
    {
        if (is_countable($this->value)) return (int) $this->value;
    }

    public function getValueIntFormattedAttribute()
    {
        if (is_numeric($this->value)) return numberFormat((int) $this->value);
    }
}
