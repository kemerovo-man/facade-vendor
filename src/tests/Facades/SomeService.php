<?php

namespace KemerovoMan\FacadeVendor\tests\Facades;

use KemerovoMan\FacadeVendor\FacadeVendor;

class SomeService extends FacadeVendor
{
    /**
     * @return \KemerovoMan\FacadeVendor\tests\Services\SomeService
     */
    public static function instance()
    {
        return parent::getFacadeRoot();
    }

    protected static function getFacadeAccessor()
    {
        return 'some.service';
    }
}