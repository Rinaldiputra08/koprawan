<?php

namespace App\Services;

class GenerateCodeService
{
    private $array;

    /**
     * Encrypt base on key (position)
     * @param string|int $random
     * 
     * @return string|int
     */
    public function encryptKeyBased($random)
    {
        $this->array = str_split($random);

        $encrypted = '';
        $prefix = '';
        foreach ($this->array as $index => $num) {
            if (is_numeric($num)) {
                $encrypted .= $index . $num;
            } else {
                $prefix .= $index . $num;
            }
        }

        $arr = str_split($encrypted);
        for ($i = 0; $i < count($arr); $i++) {
            $index = collect(array_keys($arr))->random();
            $arr[$index] = ($this->randomAbjad(1)) . $arr[$index] . ($this->randomAbjad(2));
        }

        $encrypted = implode('', $arr);

        if ($prefix != '') {
            $encrypted = $prefix . ".$encrypted";
        }
        return $encrypted;
    }

    /**
     * Decrypt for encryptKeyBased
     * @param string|int $random
     * 
     * @return string|int
     */
    public function decryptKeyBased($random)
    {
        $encrypted = explode('.', $random);
        if (count($encrypted) > 1) {
            $random = $encrypted[1];
        }
        $random = preg_replace("/[^0-9]/", "", $random);

        $decrypted = '';
        $loop = true;
        $no = 0;
        while ($loop) {
            if (strlen($random)) {
                $decrypted .= substr($random, strlen($no), 1);
                $random = substr($random, strlen($no) + 1);
            } else {
                $loop = false;
            }
            $no++;
        }

        if (count($encrypted) == 1) {
            return $decrypted;
        } else {
            return preg_replace("/[^aA-zZ]/", "", $encrypted[0]) . $decrypted;
        }
    }

    public function randomAbjad($length)
    {
        $abjad = 'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ';
        $arr = str_split($abjad);

        return collect($arr)->random($length)->implode('');
    }
}
