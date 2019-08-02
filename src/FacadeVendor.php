<?php

namespace KemerovoMan\FacadeVendor;

use Mockery;
use Illuminate\Support\Facades\Facade;
use KemerovoMan\FacadeVendor\Exceptions\SignatureException;
use ReflectionMethod;

class FacadeVendor extends Facade
{
    protected static $storedInstance = [];

    public static function checkSignature($name, $method, $shouldReceiveSignature)
    {
        $reflectionMethod = new ReflectionMethod(static::$storedInstance[$name], $method);
        $classMethod = class_basename(static::$storedInstance[$name]) . '->' . $method;
        $shouldReceiveSignatureParams = array_keys($shouldReceiveSignature);
        $reflectionSignatureParams = $reflectionMethod->getParameters();
        $signatureParams = array_map(function ($param) {
            return $param->getName();
        }, $reflectionSignatureParams);
        $requiredSignatureParams = array_filter($reflectionSignatureParams, function ($param) {
            return !$param->isOptional();
        });
        $requiredSignatureParams = array_map(function ($param) {
            return $param->getName();
        }, $requiredSignatureParams);
        if (count($requiredSignatureParams) > count($shouldReceiveSignatureParams)) {
            throw new SignatureException(
                'Signature arguments count error. Should receive '
                . count($shouldReceiveSignatureParams) . ' arguments, but signature ' .
                $classMethod . ' has ' . count($requiredSignatureParams) . ' required arguments: '
                . implode(', ', $requiredSignatureParams)
            );
        }
        if (count($shouldReceiveSignatureParams) > count($signatureParams)) {
            throw new SignatureException(
                'Signature arguments count error. Should receive '
                . count($shouldReceiveSignatureParams) . ' arguments, but signature ' .
                $classMethod . ' has ' . count($signatureParams) . ' arguments: '
                . implode(', ', $signatureParams)
            );
        }
        foreach ($shouldReceiveSignatureParams as $index => $shouldReceiveParam) {
            if ($shouldReceiveParam != $signatureParams[$index]) {
                throw new SignatureException('Signature arguments error: should receive '
                    . $shouldReceiveParam . ' argument, but ' . $signatureParams[$index]
                    . ' was received'
                );
            }
        }
    }

    public static function shouldReceive()
    {
        $method = func_get_args()[0] ?? null;
        $shouldReceiveSignature = func_get_args()[1] ?? null;
        $name = static::getFacadeAccessor();
        if (static::isMock()) {
            $mock = static::$resolvedInstance[$name];
        } else {
            static::$storedInstance[$name] = parent::getFacadeRoot();
            $mock = Mockery::mock(static::getMockableClass());
            app()->instance(static::getMockableClass(), $mock);
            static::$resolvedInstance[$name] = $mock;
        }
        if ($shouldReceiveSignature
            && is_array($shouldReceiveSignature)
            && $method
        ) {
            static::checkSignature($name, $method, $shouldReceiveSignature);
            $res = $mock->shouldReceive($method);
            call_user_func_array([$res, 'with'], array_values($shouldReceiveSignature));
            return $res;
        }
        return $mock->shouldReceive(...func_get_args());
    }
}
