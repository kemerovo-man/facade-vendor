<?php

namespace KemerovoMan\FacadeVendor;

use App\Facades\FacadeVendor;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

class LogVendorServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        app()->bind(Facade::class, function () {
            return new FacadeVendor();
        });
    }

}
