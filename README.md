# Фасад вендор для Laravel 5.7, 5.8

 Расширение фасада для возможности контролировать сигнатуру методов в юнит тестах через рефлекшн.
 
## Установка

1. выполнить
```
composer require kemerovo-man/facade-vendor
```
для Laravel 5.7
```
    "require": {
        "kemerovo-man/facade-vendor": "5.7.*"
    }
```

для Laravel 5.8
```
    "require": {
        "kemerovo-man/facade-vendor": "5.8.*"
    }
```
Пример фасада для сервиса:
```
class SomeService extends \KemerovoMan\FacadeVendor\FacadeVendor
{
    /**
     * @return \App\Services\SomeService
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
```

Пример вызова в unit тестах:
```
 SomeService::shouldReceive('someMethod', [
            'parameter1' => 'testValue1',
            'parameter2' => 'testValue2',
        ])->once();
```
