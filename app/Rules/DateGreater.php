<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class DateGreater implements Rule
{
    private $tanggal_awal;
    /**
     * Create a new rule instance.
     * @param string $tanggal_awal
     * @return void
     */
    public function __construct($tanggal_awal)
    {
        $this->tanggal_awal = $tanggal_awal;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (Carbon::create(convertDate($value)) >= Carbon::create(convertDate($this->tanggal_awal)));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Kolom :attribute harus lebih besar atau sama dengan tanggal awal.';
    }
}
