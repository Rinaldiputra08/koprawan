<?php

namespace App\Helpers;
use Exception;

class ResponseException extends Exception 
{
    private $_data = '';

    public function __construct($message, $data = null) 
    {
        $this->_data = $data;
        parent::__construct($message);
    }

    public function getData()
    {
        return $this->_data;
    }
}