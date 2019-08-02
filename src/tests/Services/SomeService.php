<?php

namespace KemerovoMan\FacadeVendor\tests\Services;

class SomeService
{
    public function someMethod($parameter1, $parameter2, $parameter3 = null)
    {
        return $parameter1 . ' ' . $parameter2 . ($parameter3 ? ' ' . $parameter3 : '');
    }
}