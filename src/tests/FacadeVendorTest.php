<?php

namespace KemerovoMan\FacadeVendor\tests;

use \KemerovoMan\FacadeVendor\Exceptions\SignatureException;
use \KemerovoMan\FacadeVendor\tests\Facades\SomeService;
use \KemerovoMan\FacadeVendor\tests\Services\SomeService as Service;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * @property Service $service
 */
class FacadeVendorTest extends TestCase
{
    private $service;

    public function setUp()
    {
        parent::setUp();

        // service provider part
        $this->service = new Service();
        app()->instance(Service::class, $this->service);
        app()->alias(Service::class, 'some.service');
        SomeService::setFacadeApplication(app());
    }

    public function testCheckSignature1()
    {
        SomeService::shouldReceive('someMethod', [
            'parameter1' => 1,
            'parameter2' => 2,
            'parameter3' => 3,
        ])->once();

        $res = $this->service
            ->someMethod(1, 2, 3);
        $this->assertEquals($res, '1 2 3');
    }

    public function testCheckSignature2()
    {
        $this->expectException(SignatureException::class);
        SomeService::shouldReceive('someMethod', [
            'parameter1' => 1,
            'wrongParameter' => 2,
            'parameter3' => 3,
        ])->once();
    }

    public function testCheckSignature3()
    {
        $this->expectException(SignatureException::class);
        SomeService::shouldReceive('someMethod', [
            'parameter1' => 1,
        ])->once();
    }

    public function testCheckSignature4()
    {
        $this->expectException(SignatureException::class);
        SomeService::shouldReceive('someMethod', [
            'parameter2' => 1,
            'parameter1' => 2,
            'parameter3' => 3,
        ])->once();
    }

    public function testCheckSignature5()
    {
        $this->expectException(ReflectionException::class);
        SomeService::shouldReceive('someWrongMethod', [
            'parameter1' => 1,
            'parameter2' => 2,
            'parameter3' => 3,
        ])->once();

        $res = $this->service
            ->someWrongMethod(1, 2, 3);
        $this->assertEquals($res, '1 2 3');
    }
}
