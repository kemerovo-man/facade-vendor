<?php

namespace KemerovoMan\FacadeVendor\Exceptions;

class SignatureException extends \Exception
{
    public function __construct($message)
    {
        $this->message = $message;
    }
}
