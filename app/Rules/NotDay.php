<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NotDay implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $day;
    public function __construct(array $day)
    {
        $this->day = $day;
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
        $day = date('D', strtotime($value));
        if (!in_array($day, $this->day)) {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $dayname = '';
        foreach ($this->day as $key => $val) {
            $dayname .= $this->getDayName($val);
            if (count($this->day) > 2) {
                if ($key < count($this->day) - 2) {
                    $dayname .= ', ';
                } elseif ($key < count($this->day) - 1) {
                    $dayname .= ' dan ';
                }
            } else if (count($this->day) > 1) {
                if ($key < count($this->day) - 1) {
                    $dayname .= ' dan ';
                }
            }
        }
        $dayname .= '.';
        return 'Kolom :attribute tidak boleh hari ' . $dayname;
    }

    private function getDayName($day)
    {
        switch ($day) {
            case 'Sun':
                $dayname = 'Minggu';
                break;
            case 'Mon':
                $dayname = 'Senin';
                break;
            case 'Tue':
                $dayname = 'Selasa';
                break;
            case 'Wed':
                $dayname = 'Rabu';
                break;
            case 'Thu':
                $dayname = 'Kamis';
                break;
            case 'Fri':
                $dayname = 'Jum\'at';
                break;
            case 'Sat':
                $dayname = 'Sabtu';
                break;
            default:
                $dayname = 'Tidak diketahui';
                break;
        }

        return $dayname;
    }
}
